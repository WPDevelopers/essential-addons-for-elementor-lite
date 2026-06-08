/**
 * Info Box — Sample E2E test
 *
 * This serves as a reference for writing EA widget tests.
 * Pattern: seed an Elementor page via template JSON, then assert
 * the widget renders expected content on the frontend.
 */
import { test, expect } from '@playwright/test';

test.describe( 'Info Box Widget', () => {
	test( 'renders title and description on the frontend', async ( { page } ) => {
		await page.goto( '/info-box-test/' );

		const infoBox = page.locator( '.eael-infobox' );
		await expect( infoBox ).toBeVisible();
		await expect( infoBox ).toContainText( 'Sample Info Box' );
		await expect( infoBox ).toContainText( 'This is a sample info box for E2E testing.' );
	} );

	test( 'renders the icon element', async ( { page } ) => {
		await page.goto( '/info-box-test/' );

		const icon = page.locator( '.eael-infobox .infobox-icon' );
		await expect( icon ).toBeVisible();
	} );
} );
