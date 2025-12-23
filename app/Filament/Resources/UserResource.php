<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $modelLabel = 'Użytkownik';

    protected static ?string $pluralModelLabel = 'Użytkownicy';

    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dane podstawowe')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Imię i nazwisko')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(20),

                        Forms\Components\FileUpload::make('avatar')
                            ->label('Zdjęcie profilowe')
                            ->image()
                            ->disk('public')
                            ->directory('avatars')
                            ->visibility('public')
                            ->imageEditor()
                            ->circleCropper()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Uprawnienia')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('Rola')
                            ->options(User::ROLES)
                            ->required()
                            ->native(false),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktywny')
                            ->default(true)
                            ->helperText('Nieaktywny użytkownik nie może się zalogować'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Hasło')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Hasło')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->helperText(fn (string $context): string => $context === 'edit'
                                ? 'Pozostaw puste, aby zachować aktualne hasło'
                                : 'Minimum 8 znaków'
                            ),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Potwierdź hasło')
                            ->password()
                            ->revealable()
                            ->same('password')
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informacje')
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Utworzono')
                            ->content(fn (?User $record): string => $record?->created_at?->format('d.m.Y H:i') ?? '-'),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Ostatnia aktualizacja')
                            ->content(fn (?User $record): string => $record?->updated_at?->format('d.m.Y H:i') ?? '-'),

                        Forms\Components\Placeholder::make('email_verified_at')
                            ->label('Email zweryfikowany')
                            ->content(fn (?User $record): string => $record?->email_verified_at?->format('d.m.Y H:i') ?? 'Nie'),
                    ])
                    ->columns(3)
                    ->hidden(fn (?User $record) => $record === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Zdjęcie')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Imię i nazwisko')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('role')
                    ->label('Rola')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => User::ROLES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'technik' => 'warning',
                        'redaktor' => 'info',
                        'lekarz' => 'success',
                        'asystent' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktywny')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email')
                    ->boolean()
                    ->getStateUsing(fn (User $record) => $record->hasVerifiedEmail())
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Aktualizacja')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Rola')
                    ->options(User::ROLES),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status aktywności'),
            ])
            ->actions([
                Tables\Actions\Action::make('sendVerification')
                    ->label('Wyślij weryfikację')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Wyślij email weryfikacyjny')
                    ->modalDescription('Czy wysłać email z linkiem weryfikacyjnym do tego użytkownika?')
                    ->visible(fn (User $record) => !$record->hasVerifiedEmail())
                    ->action(function (User $record) {
                        $record->sendEmailVerificationNotification();
                        \Filament\Notifications\Notification::make()
                            ->title('Email wysłany')
                            ->body('Link weryfikacyjny został wysłany na adres ' . $record->email)
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('verifyManually')
                    ->label('Zweryfikuj ręcznie')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Ręczna weryfikacja')
                    ->modalDescription('Czy oznaczyć email tego użytkownika jako zweryfikowany?')
                    ->visible(fn (User $record) => !$record->hasVerifiedEmail())
                    ->action(function (User $record) {
                        $record->markEmailAsVerified();
                        \Filament\Notifications\Notification::make()
                            ->title('Email zweryfikowany')
                            ->body('Adres ' . $record->email . ' został oznaczony jako zweryfikowany.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (User $record) {
                        if ($record->id === auth()->id()) {
                            throw new \Exception('Nie możesz usunąć własnego konta.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->canManageUsers() ?? false;
    }
}
