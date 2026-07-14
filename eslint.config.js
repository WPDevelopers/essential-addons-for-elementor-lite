// Flat config (ESLint 9). Lints hand-written source only — compiled assets,
// vendor libs, and tests are excluded. Enforced on changed files via CI +
// the lint-staged pre-commit hook, so the legacy tree is not reformatted wholesale.
const js = require( "@eslint/js" );
const globals = require( "globals" );

module.exports = [
	{
		ignores: [
			"assets/**",
			"node_modules/**",
			"vendor/**",
			"tools/**",
			"**/*.min.js",
			"webpack.config.js",
			"tests/**",
		],
	},
	js.configs.recommended,
	{
		files: [ "src/**/*.js" ],
		languageOptions: {
			ecmaVersion: 2021,
			sourceType: "module",
			globals: {
				...globals.browser,
				...globals.jquery,
				jQuery: "readonly",
				$: "readonly",
				elementorFrontend: "readonly",
				elementor: "readonly",
				elementorModules: "readonly",
				wp: "readonly",
				ajaxurl: "readonly",
				eael: "readonly",
				localize: "readonly",
				google: "readonly",
			},
		},
		rules: {
			// Pragmatic for a large legacy codebase: unused vars warn, real
			// undefined-reference bugs error.
			"no-unused-vars": "warn",
			"no-undef": "error",
			"no-empty": "warn",
			"no-prototype-builtins": "off",
		},
	},
];
