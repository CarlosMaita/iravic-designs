module.exports = {
    env: {
        browser: true,
        es6: true,
        node: true,
    },
    extends: [
        'eslint:recommended',
        'plugin:vue/essential'
    ],
    globals: {
        Vue: 'readonly',
        axios: 'readonly',
        $: 'readonly',
        jQuery: 'readonly',
        window: 'readonly',
        console: 'readonly',
        process: 'readonly'
    },
    parserOptions: {
        ecmaVersion: 2018,
        sourceType: 'module',
    },
    plugins: [
        'vue',
    ],
    rules: {
        // Indentation
        'indent': ['error', 4, { 'SwitchCase': 1 }],
        'vue/html-indent': ['error', 4],
        'vue/script-indent': ['error', 4],
        
        // Spacing
        'linebreak-style': ['error', 'unix'],
        'space-before-function-paren': ['error', 'never'],
        'space-in-parens': ['error', 'never'],
        'space-before-blocks': ['error', 'always'],
        'keyword-spacing': ['error'],
        
        // Quotes and semicolons
        'quotes': ['error', 'single', { 'allowTemplateLiterals': true }],
        'semi': ['error', 'always'],
        
        // Vue specific
        'vue/max-attributes-per-line': ['error', {
            'singleline': 3,
            'multiline': {
                'max': 1,
                'allowFirstLine': false
            }
        }],
        'vue/html-self-closing': ['error', {
            'html': {
                'void': 'never',
                'normal': 'any',
                'component': 'always'
            }
        }],
        'vue/order-in-components': ['error'],
        'vue/this-in-template': ['error', 'never'],
        
        // General code quality
        'no-console': ['warn'],
        'no-debugger': ['error'],
        'no-unused-vars': ['error', { 'argsIgnorePattern': '^_' }],
        'no-trailing-spaces': ['error'],
        'eol-last': ['error', 'always'],
        'comma-dangle': ['error', 'never'],
        'object-curly-spacing': ['error', 'always'],
        'array-bracket-spacing': ['error', 'never'],
    },
    overrides: [
        {
            files: ['*.vue'],
            rules: {
                'indent': 'off'
            }
        }
    ]
};