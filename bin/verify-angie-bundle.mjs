#!/usr/bin/env node
/**
 * Guard for assets/admin/js/angie-integration.min.js (see issue #883).
 *
 * The Angie MCP bundle is the only shipped artifact that inlines third-party
 * npm code wholesale, so a dependency bump changes what runs in every
 * customer's wp-admin with no corresponding code change to review. This check
 * fails the build when the bundle's dependency set or size drifts from the
 * reviewed baseline in bin/angie-bundle.allowed.json.
 *
 * To accept a deliberate change: re-run the Angie functional test (issue #877),
 * then update the baseline with `node bin/verify-angie-bundle.mjs --update`.
 */
import { readFileSync, writeFileSync, existsSync } from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = resolve( dirname( fileURLToPath( import.meta.url ) ), '..' );
const META = resolve( ROOT, '.angie-bundle-meta.json' );
const BASELINE = resolve( ROOT, 'bin/angie-bundle.allowed.json' );
const BUNDLE = 'assets/admin/js/angie-integration.min.js';

/** Bytes of headroom allowed before a size change needs re-review. */
const SIZE_TOLERANCE = 15 * 1024;

const update = process.argv.includes( '--update' );

if ( ! existsSync( META ) ) {
	fail( `${ META } not found — run \`npm run build:angie\` first.` );
}

const meta = JSON.parse( readFileSync( META, 'utf8' ) );
const output = meta.outputs[ BUNDLE ];

if ( ! output ) {
	fail( `metafile has no entry for ${ BUNDLE } (outputs: ${ Object.keys( meta.outputs ).join( ', ' ) }).` );
}

/** Roll every bundled input up to its npm package name. */
const packages = {};
for ( const [ file, info ] of Object.entries( output.inputs ) ) {
	const match = file.match( /node_modules\/((?:@[^/]+\/)?[^/]+)/ );
	if ( ! match ) {
		continue;
	}
	packages[ match[ 1 ] ] = ( packages[ match[ 1 ] ] ?? 0 ) + info.bytesInOutput;
}

const actual = {
	bytes: output.bytes,
	packages: Object.keys( packages ).sort(),
};

if ( update ) {
	const versions = {};
	for ( const name of actual.packages ) {
		versions[ name ] = readVersion( name );
	}
	writeFileSync(
		BASELINE,
		JSON.stringify( { bundle: BUNDLE, bytes: actual.bytes, sizeTolerance: SIZE_TOLERANCE, packages: versions }, null, '\t' ) + '\n'
	);
	console.log( `Baseline updated: ${ actual.packages.length } packages, ${ actual.bytes } bytes.` );
	process.exit( 0 );
}

if ( ! existsSync( BASELINE ) ) {
	fail( `${ BASELINE } not found — create it with \`node bin/verify-angie-bundle.mjs --update\`.` );
}

const baseline = JSON.parse( readFileSync( BASELINE, 'utf8' ) );
const expected = Object.keys( baseline.packages ).sort();
const problems = [];

const added = actual.packages.filter( ( name ) => ! expected.includes( name ) );
const removed = expected.filter( ( name ) => ! actual.packages.includes( name ) );

if ( added.length ) {
	problems.push( `new package(s) pulled into the bundle: ${ added.join( ', ' ) }` );
}
if ( removed.length ) {
	problems.push( `package(s) no longer in the bundle: ${ removed.join( ', ' ) }` );
}

for ( const name of actual.packages.filter( ( n ) => expected.includes( n ) ) ) {
	const installed = readVersion( name );
	if ( installed && installed !== baseline.packages[ name ] ) {
		problems.push( `${ name } is ${ installed }, baseline is ${ baseline.packages[ name ] }` );
	}
}

const drift = actual.bytes - baseline.bytes;
if ( Math.abs( drift ) > ( baseline.sizeTolerance ?? SIZE_TOLERANCE ) ) {
	problems.push(
		`bundle size moved by ${ drift > 0 ? '+' : '' }${ drift } bytes ` +
			`(${ actual.bytes } vs baseline ${ baseline.bytes })`
	);
}

if ( problems.length ) {
	fail(
		`${ BUNDLE } drifted from its reviewed baseline:\n` +
			problems.map( ( p ) => `  - ${ p }` ).join( '\n' ) +
			`\n\nThis artifact ships to every install, so review the change, re-run the Angie` +
			`\nfunctional test, then accept it with \`node bin/verify-angie-bundle.mjs --update\`.`
	);
}

console.log(
	`angie-integration.min.js OK — ${ actual.bytes } bytes, ${ actual.packages.length } bundled packages, all at baseline versions.`
);

function readVersion( name ) {
	try {
		return JSON.parse( readFileSync( resolve( ROOT, 'node_modules', name, 'package.json' ), 'utf8' ) ).version;
	} catch {
		return null;
	}
}

function fail( message ) {
	console.error( `\n[verify-angie-bundle] ${ message }\n` );
	process.exit( 1 );
}
