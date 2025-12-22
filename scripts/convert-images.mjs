/**
 * Skrypt konwersji obrazów do WebP (responsywne wersje)
 *
 * Tworzy wersje:
 * - mobile: 480px
 * - tablet: 768px
 * - desktop: 1200px
 * - original: max 1920px
 *
 * Użycie: node scripts/convert-images.mjs
 */

import sharp from 'sharp';
import { readdir, mkdir } from 'fs/promises';
import { join, basename, extname } from 'path';
import { existsSync } from 'fs';

const SIZES = {
  mobile: 480,
  tablet: 768,
  desktop: 1200,
  original: 1920
};

const INPUT_DIR = './media';
const OUTPUT_DIR = './public/images';

const CATEGORIES = {
  hero: ['beautiful-female-therapist-clinic', 'piekna-terapeutka-w-klinice'],
  services: ['laboratorium', 'lekarz_rodzinny', 'stopmatolog_uslugi', 'szczepienie_uslugi',
             'uslugi_pediatry', 'polozna', 'profilaktyka_uslugi', 'profilaktycznebadania'],
  doctors: ['PIELENGNIARKA-DOBRON', 'blond_Pielengniarka'],
  icons: ['inonka_bakteria', 'inonka_sluchawki'],
  blog: ['portrait-nurse', 'portret-pielegniarki', 'side-view-doctor', 'ai_logo_medicyn']
};

async function ensureDir(dir) {
  if (!existsSync(dir)) {
    await mkdir(dir, { recursive: true });
    console.log(`📁 Utworzono: ${dir}`);
  }
}

function getCategory(filename) {
  const name = filename.toLowerCase();
  for (const [category, patterns] of Object.entries(CATEGORIES)) {
    if (patterns.some(p => name.includes(p.toLowerCase()))) {
      return category;
    }
  }
  return 'other';
}

async function convertImage(inputPath, outputDir, filename) {
  const name = basename(filename, extname(filename));
  const category = getCategory(filename);
  const categoryDir = join(outputDir, category);

  await ensureDir(categoryDir);

  const image = sharp(inputPath);
  const metadata = await image.metadata();

  console.log(`\n🖼️  ${filename} (${metadata.width}x${metadata.height})`);

  const results = [];

  for (const [sizeName, maxWidth] of Object.entries(SIZES)) {
    // Pomijamy rozmiary większe niż oryginał
    if (metadata.width && metadata.width < maxWidth && sizeName !== 'original') {
      continue;
    }

    const outputFilename = `${name}-${sizeName}.webp`;
    const outputPath = join(categoryDir, outputFilename);

    try {
      await sharp(inputPath)
        .resize(maxWidth, null, {
          withoutEnlargement: true,
          fit: 'inside'
        })
        .webp({
          quality: sizeName === 'mobile' ? 75 : 80,
          effort: 6
        })
        .toFile(outputPath);

      const outputMeta = await sharp(outputPath).metadata();
      const inputSize = (await sharp(inputPath).metadata()).size || 0;
      const savings = inputSize > 0 ? Math.round((1 - outputMeta.size / inputSize) * 100) : 0;

      console.log(`   ✅ ${sizeName}: ${outputMeta.width}x${outputMeta.height} (${Math.round(outputMeta.size/1024)}KB)`);

      results.push({
        size: sizeName,
        width: outputMeta.width,
        height: outputMeta.height,
        path: outputPath,
        fileSize: outputMeta.size
      });
    } catch (err) {
      console.log(`   ❌ ${sizeName}: ${err.message}`);
    }
  }

  return { filename, category, results };
}

async function generateSrcsetHelper(conversions) {
  const srcsetMap = {};

  for (const conv of conversions) {
    if (!srcsetMap[conv.category]) {
      srcsetMap[conv.category] = {};
    }

    const baseName = conv.filename.replace(/\.[^.]+$/, '');
    srcsetMap[conv.category][baseName] = conv.results.map(r => ({
      size: r.size,
      width: r.width,
      path: r.path.replace('public/', '/')
    }));
  }

  return srcsetMap;
}

async function main() {
  console.log('🚀 Konwersja obrazów do WebP\n');
  console.log('Rozmiary:', SIZES);
  console.log('');

  await ensureDir(OUTPUT_DIR);

  const files = await readdir(INPUT_DIR);
  const imageFiles = files.filter(f =>
    /\.(jpg|jpeg|png|webp)$/i.test(f) &&
    !f.startsWith('.')
  );

  console.log(`📂 Znaleziono ${imageFiles.length} obrazów w ${INPUT_DIR}\n`);

  const conversions = [];
  let totalSaved = 0;

  for (const file of imageFiles) {
    const inputPath = join(INPUT_DIR, file);
    try {
      const result = await convertImage(inputPath, OUTPUT_DIR, file);
      conversions.push(result);
    } catch (err) {
      console.log(`❌ Błąd przy ${file}: ${err.message}`);
    }
  }

  // Podsumowanie
  console.log('\n' + '='.repeat(50));
  console.log('📊 PODSUMOWANIE\n');

  const byCategory = {};
  for (const conv of conversions) {
    if (!byCategory[conv.category]) byCategory[conv.category] = 0;
    byCategory[conv.category]++;
  }

  for (const [cat, count] of Object.entries(byCategory)) {
    console.log(`   ${cat}: ${count} obrazów`);
  }

  console.log(`\n✅ Przekonwertowano ${conversions.length} obrazów`);
  console.log(`📁 Wynik w: ${OUTPUT_DIR}`);

  // Generuj helper do srcset
  const srcsetData = await generateSrcsetHelper(conversions);

  const helperContent = `// Auto-generated srcset helper
// Użycie w Svelte: import { images } from '$lib/images';

export const images = ${JSON.stringify(srcsetData, null, 2)};

export function getSrcset(category, name) {
  const img = images[category]?.[name];
  if (!img) return '';
  return img.map(i => \`\${i.path} \${i.width}w\`).join(', ');
}

export function getSizes(breakpoints = { mobile: 480, tablet: 768 }) {
  return \`(max-width: \${breakpoints.mobile}px) \${breakpoints.mobile}px, (max-width: \${breakpoints.tablet}px) \${breakpoints.tablet}px, 1200px\`;
}
`;

  await ensureDir('./src/lib');
  const { writeFile } = await import('fs/promises');
  await writeFile('./src/lib/images.js', helperContent);
  console.log('\n📝 Wygenerowano: src/lib/images.js');
}

main().catch(console.error);
