module.exports = {
    root: true,
    env: {
        browser: true,
        es6: true,
        node: true,
    },
    extends: [
        "eslint:recommended",
        "plugin:@wordpress/eslint-plugin/recommended"
    ],
    parserOptions: {
        ecmaVersion: 2020,
        sourceType: "module",
    },
    globals: {
        jQuery: "readonly",
        linkedinPreview: "readonly",
    },
    rules: {
        "no-console": "off", // Falls Debugging gewünscht
        "@wordpress/no-unused-vars-before-return": "warn",
        "no-undef": "error"
    },
};