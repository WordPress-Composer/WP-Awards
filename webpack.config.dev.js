const path = require('path');

module.exports = {
    watch: true,
    watchOptions: {
        ignored: /node_modules/
    },
    devtool: 'source-map',
    entry: [
        "./assets/js/app.js",
        "./assets/scss/style.scss"
    ],
    output: {
        path: path.resolve(__dirname, "dist"),
        filename: "client.js"
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    //postcss: [require('postcss-cssnext')()]
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
            }
        ]
    }
}