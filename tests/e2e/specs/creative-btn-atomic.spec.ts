/**
 * Creative Button (Atomic / V4) — E2E test
 *
 * The atomic widget only registers when Elementor's "e_atomic_elements"
 * experiment is active (enabled by seed.sh). The page is seeded from
 * templates/creative-btn-atomic.json with the Winona effect + secondary text.
 */
import { test, expect } from '@playwright/test';

test.describe( 'Creative Button (Atomic) Widget', () => {
	test( 'renders the button with primary text', async ( { page } ) => {
		await page.goto( '/creative-btn-atomic-test/' );

		const button = page.locator( '.eael-creative-button--atomic' );
		await expect( button ).toBeVisible();
		await expect( button ).toContainText( 'Atomic CTA' );
	} );

	test( 'applies the selected effect and atomic marker classes', async ( { page } ) => {
		await page.goto( '/creative-btn-atomic-test/' );

		const button = page.locator( '.eael-creative-button--atomic' );
		await expect( button ).toHaveClass( /eael-creative-button--winona/ );
		await expect( button ).toHaveClass( /eael-creative-button--has-secondary/ );
	} );

	test( 'exposes secondary text via data-text', async ( { page } ) => {
		await page.goto( '/creative-btn-atomic-test/' );

		const button = page.locator( '.eael-creative-button--atomic' );
		await expect( button ).toHaveAttribute( 'data-text', 'Go Atomic!' );
	} );

	test( 'renders as a <button> when no link is set', async ( { page } ) => {
		await page.goto( '/creative-btn-atomic-test/' );

		const button = page.locator( 'button.eael-creative-button--atomic' );
		await expect( button ).toBeVisible();
	} );
} );
