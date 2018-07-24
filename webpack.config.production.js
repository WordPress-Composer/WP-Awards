const path = require('path');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const SimpleProgressPlugin = require('webpack-simple-progress-plugin');

module.exports = {
    entry: [
        "babel-polyfill",
        "./assets/js/app.js",
        "./assets/scss/style.scss"
    ],
    output: {
        path: path.resolve(__dirname, "dist"),
        filename: "client.js"
    },
    plugins: [
        new UglifyJsPlugin(),
        new SimpleProgressPlugin()
    ],
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    postcss: [require('postcss-cssnext')()]
                }
            },
            {
                test: /\.scss$/,
                use: [{
                    loader: "style-loader" // creates style nodes from JS strings
                }, {
                    loader: "css-loader" // translates CSS into CommonJS
                }, {
                    loader: "sass-loader" // compiles Sass to CSS
                }]
            },
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['babel-preset-env']
                    }
                }
            }
        ]
    }
}