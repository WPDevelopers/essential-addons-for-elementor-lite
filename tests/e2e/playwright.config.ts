import { defineConfig, devices } from '@playwright/test';

export default defineConfig( {
	testDir: './specs',
	fullyParallel: false,
	forbidOnly: !! process.env.CI,
	retries: process.env.CI ? 2 : 0,
	workers: 1,
	reporter: 'html',
	globalSetup: './global-setup.ts',
	use: {
		baseURL: 'http://localhost:8888',
		trace: 'on-first-retry',
		screenshot: 'only-on-failure',
	},
	projects: [
		{
			name: 'chromium',
			use: { ...devices[ 'Desktop Chrome' ] },
		},
	],
} );
