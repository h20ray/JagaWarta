import * as esbuild from 'esbuild';
import { readdirSync, existsSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const srcDir = join(__dirname, 'assets', 'src');
const distDir = join(__dirname, 'assets', 'dist');

const watch = process.argv.includes('--watch');

async function build() {
  if (!existsSync(srcDir)) return;
  console.log('Building JS in:', srcDir);
  const entries = readdirSync(srcDir).filter((f) => f.endsWith('.js'));
  console.log('Found entries:', entries);
  const entryPoints = entries.map((e) => join(srcDir, e));
  if (entryPoints.length === 0) return;

  const opts = {
    entryPoints,
    bundle: true,
    format: 'iife',
    outdir: distDir,
    minify: !watch,
    target: ['es2020'],
  };
  if (watch) {
    const ctx = await esbuild.context(opts);
    await ctx.watch();
  } else {
    await esbuild.build(opts);
  }
}

build().catch((err) => {
  console.error(err);
  process.exit(1);
});
