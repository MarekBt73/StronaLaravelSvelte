<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    protected $signature = 'mail:test {email : Email address to send test to}';

    protected $description = 'Send a test email to verify mail configuration';

    public function handle(): int
    {
        $email = $this->argument('email');

        $this->info("Sending test email to: {$email}");
        $this->info("Using mailer: " . config('mail.default'));
        $this->info("SMTP Host: " . config('mail.mailers.smtp.host'));

        try {
            Mail::raw(
                "To jest testowy email z aplikacji MedVita.\n\n" .
                "Jeśli widzisz tę wiadomość, konfiguracja email działa poprawnie.\n\n" .
                "Data wysłania: " . now()->format('Y-m-d H:i:s') . "\n" .
                "Środowisko: " . config('app.env') . "\n\n" .
                "Pozdrawiamy,\n" .
                "Zespół MedVita",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Test email - MedVita');
                }
            );

            $this->info('Email sent successfully!');
            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to send email:');
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
