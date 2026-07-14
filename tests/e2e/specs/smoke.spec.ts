/**
 * Manifest-driven smoke suite.
 *
 * Generates one test per entry in tests/e2e/manifest.json: load the widget's
 * seeded page and assert its root element renders and contains expected text.
 * This is the scalable backfill path — grow coverage by adding template + manifest
 * entries, not by hand-writing a spec per widget. Reach for a bespoke spec (e.g.
 * info-box.spec.ts) only when a widget needs richer assertions.
 */
import { test, expect } from '@playwright/test';
import manifest from '../manifest.json';

type WidgetCase = {
	slug: string;
	selector: string;
	contains?: string[];
};

const widgets = ( manifest.widgets ?? [] ) as WidgetCase[];

test.describe( 'Widget smoke suite', () => {
	if ( widgets.length === 0 ) {
		test.skip( 'no widgets in manifest', () => {} );
	}

	for ( const widget of widgets ) {
		test( `${ widget.slug } renders on the frontend`, async ( { page } ) => {
			await page.goto( `/${ widget.slug }-test/` );

			const root = page.locator( widget.selector ).first();
			await expect( root, `${ widget.slug }: ${ widget.selector } should be visible` ).toBeVisible();

			for ( const text of widget.contains ?? [] ) {
				await expect( root ).toContainText( text );
			}
		} );
	}
} );
