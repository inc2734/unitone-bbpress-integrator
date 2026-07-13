const defaultConfig = require( '@wordpress/scripts/config/eslint.config.cjs' );

module.exports = [
	{
		ignores: [ 'dist/**' ],
	},
	...defaultConfig,
	{
		files: [ '**/*.{js,jsx,mjs,cjs}' ],
		rules: {
			'import/no-extraneous-dependencies': 'off',
			'import/no-unresolved': 'off',
			'import/default': 'off',
			'import/named': 'off',
		},
	},
];
