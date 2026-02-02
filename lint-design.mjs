/**
 * Design-system QA: fail on hard-coded colors (hex, rgb, hsl) outside allowed files.
 * @package JagaWarta
 */

import { readFileSync, readdirSync, statSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const root = __dirname;

const ALLOWED = [
	/assets\/dist\/tokens\.css$/,
	/build-tokens\.mjs$/,
	/\\design\\/,
	/\/design\//,
	/\.md$/,
];

const HEX = /#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})\b/g;
const RGB_HSL = /\b(rgba?|hsla?)\s*\(/g;

function* walk(dir, ext = null) {
	const entries = readdirSync(dir, { withFileTypes: true });
	for (const e of entries) {
		const full = join(dir, e.name);
		if (e.name === 'node_modules' || e.name === '.git') continue;
		if (e.isDirectory()) {
			yield* walk(full, ext);
		} else if (!ext || e.name.endsWith(ext)) {
			yield full;
		}
	}
}

function isAllowed(path) {
	const norm = path.replace(/\\/g, '/');
	return ALLOWED.some((re) => re.test(norm));
}

let failed = 0;
const check = (path) => {
	if (isAllowed(path)) return;
	let content;
	try {
		content = readFileSync(path, 'utf8');
	} catch {
		return;
	}
	const lines = content.split('\n');
	lines.forEach((line, i) => {
		let m;
		HEX.lastIndex = 0;
		while ((m = HEX.exec(line)) !== null) {
			console.error(`${path}:${i + 1}: hex color ${m[0]} (use token classes)`);
			failed++;
		}
		RGB_HSL.lastIndex = 0;
		while ((m = RGB_HSL.exec(line)) !== null) {
			console.error(`${path}:${i + 1}: ${m[1]}() color (use token classes)`);
			failed++;
		}
	});
};

for (const f of walk(root, '.php')) check(f);
for (const f of walk(root, '.css')) {
	if (f.includes('dist')) continue;
	check(f);
}
for (const f of walk(root, '.js')) {
	if (f.includes('node_modules')) continue;
	check(f);
}
for (const f of walk(root, '.mjs')) check(f);

process.exit(failed > 0 ? 1 : 0);
