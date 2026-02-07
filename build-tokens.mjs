/**
 * Emits tokens.css from design/tokens.json (color) + tokens/*.json. Sys + role layer.
 * @package JagaWarta
 */

import { readFileSync, writeFileSync, mkdirSync, existsSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const rootDir = __dirname;
const tokensDir = join(rootDir, 'tokens');
const designDir = join(rootDir, 'design');
const outDir = join(rootDir, 'assets', 'dist');
const outFile = join(outDir, 'tokens.css');

const palette = {
  'neutral-0': '#000000',
  'neutral-4': '#0d0d0d',
  'neutral-10': '#1a1a1a',
  'neutral-12': '#1f1f1f',
  'neutral-17': '#2b2b2b',
  'neutral-20': '#323232',
  'neutral-22': '#383838',
  'neutral-24': '#3d3d3d',
  'neutral-30': '#4d4d4d',
  'neutral-50': '#797979',
  'neutral-60': '#949494',
  'neutral-80': '#c9c9c9',
  'neutral-90': '#e6e6e6',
  'neutral-92': '#ececec',
  'neutral-94': '#f2f2f2',
  'neutral-95': '#f5f5f5',
  'neutral-96': '#f7f7f7',
  'neutral-99': '#fafafa',
  'neutral-100': '#ffffff',
  'primary-10': '#001b3d',
  'primary-20': '#003062',
  'primary-30': '#00468a',
  'primary-40': '#1e3a5f',
  'primary-80': '#5c8fd6',
  'primary-90': '#d3e3fd',
  'primary-100': '#e8f0fe',
  'secondary-10': '#1a1a1a',
  'secondary-20': '#2d2d2d',
  'secondary-30': '#434343',
  'secondary-40': '#5c5c5c',
  'secondary-80': '#c4c4c4',
  'secondary-90': '#e0e0e0',
  'secondary-100': '#f5f5f5',
  'tertiary-30': '#3d2d00',
  'tertiary-40': '#5c4500',
  'tertiary-80': '#e5c44d',
  'tertiary-90': '#ffdf9e',
  'error-10': '#410002',
  'error-30': '#93000a',
  'error-40': '#ba1a1a',
  'error-80': '#ffb4ab',
  'error-90': '#ffdad6',
  'error-99': '#fffbff',
};

function loadJson(dir, name) {
  const path = join(dir, name);
  if ( ! existsSync(path)) return null;
  return JSON.parse(readFileSync(path, 'utf8'));
}

function camelToKebab(str) {
  return str.replace(/([A-Z])/g, '-$1').toLowerCase().replace(/^-/, '');
}

function emitColorVars(scheme, selector) {
  const designTokens = loadJson(designDir, 'tokens.json');
  const legacyColor = loadJson(tokensDir, 'color.json');
  const designRoles = designTokens?.color?.[scheme] || {};
  const legacyRoles = legacyColor?.[scheme] || {};
  const roles = { ...legacyRoles, ...designRoles };
  const lines = [];
  for (const [role, ref] of Object.entries(roles)) {
    const value = palette[ref] || ref;
    lines.push(`  --md-sys-color-${camelToKebab(role)}: ${value};`);
  }
  return `${selector} {\n${lines.join('\n')}\n}\n`;
}

function emitRoleLayer() {
  const designTokens = loadJson(designDir, 'tokens.json');
  const colorRoles = designTokens?.color?.light || {};
  const lines = [];
  for (const role of Object.keys(colorRoles)) {
    const name = camelToKebab(role);
    lines.push(`  --md-color-${name}: var(--md-sys-color-${name});`);
  }
  const shape = loadJson(tokensDir, 'shape.json');
  if (shape) {
    lines.push('');
    lines.push('  /* Shape roles */');
    for (const [k, v] of Object.entries(shape)) {
      const name = k.replace('shape-corner-', 'corner-');
      lines.push(`  --md-shape-${name}: ${v};`);
    }
  }
  return `:root {\n${lines.join('\n')}\n}\n`;
}

function emitNonColorVars() {
  const typo = loadJson(tokensDir, 'typography.json');
  const shape = loadJson(tokensDir, 'shape.json');
  const motion = loadJson(tokensDir, 'motion.json');
  const spacing = loadJson(tokensDir, 'spacing.json');
  const state = loadJson(tokensDir, 'state.json');
  const elevation = loadJson(tokensDir, 'elevation.json');
  if ( ! typo || ! shape || ! motion || ! spacing || ! state || ! elevation ) return ':root {}';
  const lines = [];
  for (const [scale, sizes] of Object.entries(typo)) {
    if (scale === 'fontFamily') continue;
    for (const [size, props] of Object.entries(sizes)) {
      const prefix = `--md-sys-typescale-${scale}-${size}`;
      if (props.size) lines.push(`  ${prefix}-size: ${props.size};`);
      if (props.lineHeight) lines.push(`  ${prefix}-line-height: ${props.lineHeight};`);
      if (props.weight) lines.push(`  ${prefix}-weight: ${props.weight};`);
    }
  }
  lines.push(`  --md-sys-font-family-sans: ${typo.fontFamily.sans};`);
  lines.push(`  --md-sys-font-family-serif: ${typo.fontFamily.serif};`);
  for (const [k, v] of Object.entries(shape)) {
    lines.push(`  --md-sys-shape-${k.replace('shape-corner-', 'corner-')}: ${v};`);
  }
  for (const [k, v] of Object.entries(motion)) {
    lines.push(`  --md-sys-motion-${k.replace('motion-', '')}: ${v};`);
  }
  for (const [k, v] of Object.entries(spacing)) {
    lines.push(`  --md-sys-${k}: ${v};`);
  }
  for (const [k, v] of Object.entries(state)) {
    const val = v === 'primary' ? 'var(--md-sys-color-primary)' : v;
    lines.push(`  --md-sys-${k}: ${val};`);
  }
  for (const [k, v] of Object.entries(elevation)) {
    lines.push(`  --md-sys-${k}: ${v};`);
  }
  const layout = loadJson(tokensDir, 'layout.json');
  if (layout) {
    for (const [k, v] of Object.entries(layout)) {
      lines.push(`  --md-sys-${k}: ${v};`);
    }
  }
  return `:root {\n${lines.join('\n')}\n}\n`;
}

function build() {
  mkdirSync(outDir, { recursive: true });
  const lightColorBlock = emitColorVars('light', ':root');
  const lightColorLines = lightColorBlock.match(/^\s*--[^\n]+/gm) || [];
  const nonColorBlock = emitNonColorVars();
  const nonColorLines = nonColorBlock.match(/^\s*--[^\n]+/gm) || [];
  const rootLines = [...lightColorLines, ...nonColorLines];
  const rootCss = `:root {\n${rootLines.join('\n')}\n}\n`;
  const roleCss = emitRoleLayer();
  const parts = [
    '/* Generated by build-tokens.mjs – do not edit */',
    '',
    rootCss,
    emitColorVars('dark', '[data-theme="dark"]'),
    '',
    '/* Role layer: components use --md-color-* only */',
    roleCss,
  ];
  writeFileSync(outFile, parts.join('\n'), 'utf8');
}

build();
