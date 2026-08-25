/**
 * Essential Addons — Angie MCP server.
 *
 * Registers a browser-side MCP server with the Angie AI assistant so it can
 * discover the EA widgets installed on this site and fetch their control
 * schemas when generating Elementor designs.
 *
 * Built standalone with esbuild (npm run build:angie) into
 * assets/admin/js/angie-integration.min.js — NOT part of the webpack pipeline.
 * Enqueued only when the Angie plugin is active (see Angie_Integration.php).
 */
import { AngieMcpSdk } from '@elementor/angie-sdk';
import { McpServer } from '@modelcontextprotocol/sdk/server/mcp.js';
import { z } from 'zod';

const SERVER_NAME = 'essential-addons';

async function ajaxRequest(action, data = {}) {
	const settings = window.eaelAngie || {};
	const body = new FormData();

	body.append('action', action);
	body.append('nonce', settings.nonce || '');

	Object.entries(data).forEach(([key, value]) => {
		body.append(key, value);
	});

	const response = await fetch(settings.ajaxUrl || '/wp-admin/admin-ajax.php', {
		method: 'POST',
		credentials: 'same-origin',
		body,
	});

	if (!response.ok) {
		throw new Error(`Essential Addons request failed: HTTP ${response.status}`);
	}

	const json = await response.json();

	if (!json.success) {
		throw new Error(json.data?.message || 'Essential Addons request failed.');
	}

	return json.data;
}

function asToolResult(data) {
	return {
		content: [{ type: 'text', text: JSON.stringify(data, null, 2) }],
	};
}

function createServer() {
	const server = new McpServer(
		{
			name: SERVER_NAME,
			title: 'Essential Addons for Elementor',
			version: (window.eaelAngie && window.eaelAngie.version) || '1.0.0',
		},
		{
			capabilities: { tools: {} },
			instructions: [
				'This site runs Essential Addons for Elementor (EA), a library of 100+ Elementor widgets:',
				'accordions, tabs, post grids, pricing tables, testimonials, galleries, forms styling,',
				'countdowns, WooCommerce layouts and more. Their Elementor widgetType names are prefixed',
				'with "eael-".',
				'',
				'When building or editing Elementor pages/sections on this site, PREFER an EA widget over',
				'hand-rolled HTML or a generic core widget whenever one matches the requested design',
				'(e.g. pricing table, testimonial slider, post grid, advanced accordion).',
				'',
				'Workflow: call list-ea-widgets to see what is installed and active, then call',
				'get-ea-widget-schema for the exact widget BEFORE writing its settings into',
				'_elementor_data — never guess EA control names or values. Use the returned control',
				'names, types and defaults exactly as given.',
				'',
				'This server is read-only: it describes widgets but does not modify the site.',
			].join('\n'),
		}
	);

	server.registerTool(
		'list-ea-widgets',
		{
			description:
				'Lists all active Essential Addons widgets on this site (name = Elementor widgetType, ' +
				'title, keywords). Call this first to know which EA widgets are available for a design.',
			inputSchema: {},
			annotations: { readOnlyHint: true },
		},
		async () => asToolResult(await ajaxRequest('eael_angie_widget_catalog'))
	);

	server.registerTool(
		'get-ea-widget-schema',
		{
			description:
				'Returns the control schema (control names, types, options, defaults) for one Essential ' +
				'Addons widget. Always call this before writing an eael-* widget into _elementor_data. ' +
				'By default returns Content-tab controls; pass tab="all" for style controls too.',
			inputSchema: {
				widget: z.string().describe('EA widget name from list-ea-widgets, e.g. "eael-adv-accordion"'),
				tab: z.enum(['content', 'all']).optional().describe('Which control tabs to include (default "content")'),
			},
			annotations: { readOnlyHint: true },
		},
		async ({ widget, tab }) =>
			asToolResult(await ajaxRequest('eael_angie_widget_schema', { widget, tab: tab || 'content' }))
	);

	return server;
}

(async () => {
	try {
		const sdk = new AngieMcpSdk();

		await sdk.waitForReady();

		await sdk.registerServer({
			name: SERVER_NAME,
			version: (window.eaelAngie && window.eaelAngie.version) || '1.0.0',
			description: 'Essential Addons widget discovery for Elementor design generation',
			server: createServer(),
		});
	} catch (error) {
		// Angie not present or not ready — fail silently, EA works fine without it.
		if (window.console && console.debug) {
			console.debug('[EA] Angie MCP registration skipped:', error);
		}
	}
})();
