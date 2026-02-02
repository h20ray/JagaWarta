import { readdirSync, statSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';
import { execSync } from 'child_process';

const __dirname = dirname(fileURLToPath(import.meta.url));
const root = __dirname;

function findPhp(dir, list = []) {
  const entries = readdirSync(dir, { withFileTypes: true });
  for (const e of entries) {
    const full = join(dir, e.name);
    if (e.name === 'node_modules') continue;
    if (e.isDirectory()) findPhp(full, list);
    else if (e.name.endsWith('.php')) list.push(full);
  }
  return list;
}

const files = findPhp(root);
let failed = 0;
for (const f of files) {
  try {
    execSync(`php -l "${f}"`, { stdio: 'pipe' });
  } catch (err) {
    console.error(f, err.stderr?.toString() || err.message);
    failed++;
  }
}
process.exit(failed > 0 ? 1 : 0);
