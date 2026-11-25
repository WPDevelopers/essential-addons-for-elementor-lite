/**
 * Advanced Tabs — responsive "Vertical Layout" migration (#1043)
 *
 * Covers the backward-compatibility migration that ships with the fix for #1043
 * (Migration::migrate_adv_tabs_responsive_vertical_layout(), includes/Classes/Migration.php):
 *
 *   - The render condition for the `responsive_vertical_layout` control was flipped to
 *     match its label. Since the control defaults to 'yes' and Elementor only persists a
 *     control once a user touches it, every pre-fix Advanced Tabs widget has the key
 *     missing and would have had its mobile layout silently flipped.
 *   - The migration backfills an explicit 'no' into any such pre-existing widget, but must
 *     NOT touch a genuinely new widget created after the fix shipped — including one still
 *     sitting in Draft, which WordPress gives a zeroed `post_date_gmt` until first publish.
 *
 * These specs drive the migration directly via `wp eval` (rather than waiting on WP-Cron)
 * so each case is deterministic: the shared `eael_adv_tabs_responsive_layout_cutoff` /
 * `eael_adv_tabs_responsive_layout_migrated` options are reset before every test, and the
 * "before/after the cutoff" distinction is made by moving the cutoff relative to the
 * fixture (±1 hour) instead of waiting on real time to pass.
 */
import { test, expect } from '@playwright/test';
import { execSync } from 'child_process';

const TEMPLATE_DIR = '/var/www/html/wp-content/plugins/essential-addons-for-elementor-lite/tests/e2e/templates';
const TEMPLATE = 'adv-tabs-no-layout-key.json';

const CUTOFF_OPTION = 'eael_adv_tabs_responsive_layout_cutoff';
const MIGRATED_OPTION = 'eael_adv_tabs_responsive_layout_migrated';
const MIGRATED_META_KEY = '_eael_adv_tabs_responsive_layout_migrated';

/** Run a wp-cli command inside the wp-env `cli` container and return trimmed stdout. */
function wp( args: string ): string {
	return execSync( `npx wp-env run cli wp ${ args } --allow-root`, { encoding: 'utf8' } ).trim();
}

/**
 * Run a wp-cli command that needs container-side shell features (here, `$(cat …)` to read
 * a template file that only exists inside the container). Wrapping in `bash -c` makes the
 * substitution happen on the container's shell instead of the host's.
 */
function wpShell( innerCommand: string ): string {
	const escaped = innerCommand.replace( /'/g, `'\\''` );
	return execSync( `npx wp-env run cli bash -c '${ escaped } --allow-root'`, { encoding: 'utf8' } ).trim();
}

/** `wp eval` runs arbitrary PHP (no opening `<?php` tag); single quotes in `php` are escaped for the shell. */
function wpEval( php: string ): string {
	const escaped = php.replace( /'/g, `'\\''` );
	return execSync( `npx wp-env run cli wp eval '${ escaped }' --allow-root`, { encoding: 'utf8' } ).trim();
}

/** Current server time, as GMT, offset by `offsetSeconds` (negative = past, positive = future). */
function serverGmTime( offsetSeconds: number ): string {
	return wpEval( `echo gmdate( 'Y-m-d H:i:s', time() + ( ${ offsetSeconds } ) );` );
}

function setCutoff( gmDateString: string ): void {
	wpEval( `update_option( '${ CUTOFF_OPTION }', '${ gmDateString }' );` );
}

function resetMigrationState(): void {
	wp( `option delete ${ CUTOFF_OPTION }` );
	wp( `option delete ${ MIGRATED_OPTION }` );
	// `wp cron event delete` exits non-zero when nothing is scheduled; that's expected most of the time.
	try {
		wp( `cron event delete eael_migrate_adv_tabs_responsive_layout` );
	} catch {
		// nothing scheduled — fine.
	}
}

function runMigrationOnce(): void {
	wpEval(
		'$m = new \\Essential_Addons_Elementor\\Classes\\Migration(); ' +
			'$m->migrate_adv_tabs_responsive_vertical_layout();'
	);
}

function createAdvTabsPage( status: 'publish' | 'draft' ): number {
	const id = Number(
		wp(
			`post create --post_type=page --post_title="AdvTabs Migration Test ${ Date.now() }" --post_status=${ status } --porcelain`
		)
	);
	wp( `post meta update ${ id } _elementor_edit_mode builder` );
	wp( `post meta update ${ id } _elementor_template_type wp-page` );
	wp( `post meta update ${ id } _elementor_version "3.25.0"` );
	wpShell( `wp post meta update ${ id } _elementor_data "$(cat ${ TEMPLATE_DIR }/${ TEMPLATE })"` );
	return id;
}

/**
 * Simulate Elementor's own document save: it always writes `_elementor_data` as part of a
 * real `wp_update_post()` call. That matters specifically for a draft/auto-draft/pending
 * post — WordPress only stamps a real `post_modified_gmt` on an *update* (not the initial
 * insert), so a draft fixture built purely from `post create` + raw `post meta update`
 * (bypassing `wp_update_post()`) doesn't represent how a real draft ever reaches this
 * migration's LIKE scan in production.
 */
function touchViaRealUpdate( postId: number ): void {
	wp( `post update ${ postId } --post_excerpt="migration test touch ${ Date.now() }"` );
}

function readAdvTabsSettings( postId: number ): Record<string, unknown> | null {
	const raw = wp( `post meta get ${ postId } _elementor_data` );
	const data = JSON.parse( raw.slice( raw.indexOf( '[' ) ) );

	let found: Record<string, unknown> | null = null;
	const walk = ( elements: any[] ) => {
		for ( const el of elements ) {
			if ( el.widgetType === 'eael-adv-tabs' ) {
				found = el.settings;
			}
			if ( el.elements ) {
				walk( el.elements );
			}
		}
	};
	walk( data );
	return found;
}

function deletePost( postId: number ): void {
	wp( `post delete ${ postId } --force` );
}

test.describe( 'Advanced Tabs responsive-layout migration (#1043)', () => {
	test.beforeEach( () => {
		resetMigrationState();
	} );

	test.afterEach( () => {
		resetMigrationState();
	} );

	test( 'published post created before the cutoff is backfilled to "no"', async ( { page } ) => {
		const id = createAdvTabsPage( 'publish' );
		// Force a real pre-cutoff creation date (this status isn't "date floating", so
		// post_date_gmt is set for real).
		wp( `post update ${ id } --post_date="2020-01-01 00:00:00"` );

		setCutoff( serverGmTime( 0 ) );
		runMigrationOnce();

		const settings = readAdvTabsSettings( id );
		expect( settings?.responsive_vertical_layout ).toBe( 'no' );

		// Render check: 'no' means the forcing class is absent, i.e. the pre-fix
		// (wrapped/side-by-side) mobile appearance is preserved.
		const slug = wp( `post list --include=${ id } --field=post_name` );
		await page.goto( `/${ slug }/` );
		const wrapper = page.locator( '.eael-advance-tabs' ).first();
		await expect( wrapper ).not.toHaveClass( /responsive-vertical-layout/ );

		deletePost( id );
	} );

	test( 'published post created after the cutoff is left untouched (new default applies)', async ( { page } ) => {
		setCutoff( serverGmTime( -3600 ) ); // an hour in the past
		const id = createAdvTabsPage( 'publish' ); // post_date_gmt defaults to "now", i.e. after the cutoff

		runMigrationOnce();

		const settings = readAdvTabsSettings( id );
		expect( settings?.responsive_vertical_layout ).toBeUndefined();

		// Render check: missing key resolves to the control's default ('yes'), which under
		// the fixed condition means the forcing class IS present (stacked on mobile).
		const slug = wp( `post list --include=${ id } --field=post_name` );
		await page.goto( `/${ slug }/` );
		const wrapper = page.locator( '.eael-advance-tabs' ).first();
		await expect( wrapper ).toHaveClass( /responsive-vertical-layout/ );

		deletePost( id );
	} );

	test( 'draft post saved before the cutoff is still backfilled, despite never being published', async () => {
		const id = createAdvTabsPage( 'draft' );
		// post_date_gmt stays the zero-date for a draft regardless of --post_date — only a
		// real wp_update_post() call stamps post_modified_gmt, which is what the migration
		// falls back to for a zero post_date_gmt.
		touchViaRealUpdate( id );

		setCutoff( serverGmTime( 3600 ) ); // an hour in the future — this draft's real save is "before" it
		runMigrationOnce();

		const settings = readAdvTabsSettings( id );
		expect( settings?.responsive_vertical_layout ).toBe( 'no' );

		deletePost( id );
	} );

	test( 'draft post saved after the cutoff is protected from the backfill', async () => {
		setCutoff( serverGmTime( -3600 ) ); // an hour in the past
		const id = createAdvTabsPage( 'draft' );
		touchViaRealUpdate( id ); // real post_modified_gmt = now, i.e. after the cutoff

		runMigrationOnce();

		const settings = readAdvTabsSettings( id );
		expect( settings?.responsive_vertical_layout ).toBeUndefined();

		deletePost( id );
	} );

	test( 'a row with non-JSON _elementor_data does not block the migration from completing', async () => {
		const id = Number(
			wp(
				`post create --post_type=page --post_title="AdvTabs Corrupt Data Test ${ Date.now() }" --post_status=publish --porcelain`
			)
		);
		// Legacy serialized-PHP shape, or any other corruption — still matches the LIKE scan
		// (contains the widget-type string) but won't json_decode() to an array.
		wpShell( `wp post meta update ${ id } _elementor_data 'a:1:{s:10:"widgetType";s:13:"eael-adv-tabs";}'` );

		setCutoff( serverGmTime( 3600 ) ); // make sure this row is in scope
		runMigrationOnce();
		runMigrationOnce(); // a second pass must find nothing new and reach completion

		expect( wp( `option get ${ MIGRATED_OPTION }` ) ).toBe( '1' );
		expect( wp( `post meta get ${ id } ${ MIGRATED_META_KEY }` ) ).toBe( '1' );

		deletePost( id );
	} );
} );
