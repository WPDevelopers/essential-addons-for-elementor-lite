import { execSync } from 'child_process';

export default function globalSetup() {
	const pluginRoot = process.cwd().replace( /\/tests\/e2e$/, '' );
	execSync(
		'wp-env run cli bash /var/www/html/wp-content/plugins/essential-addons-for-elementor-lite/tests/e2e/utils/seed.sh',
		{ cwd: pluginRoot, stdio: 'inherit', timeout: 120_000 }
	);
}
