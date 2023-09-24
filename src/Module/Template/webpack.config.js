const path = require('path')
const webpack = require('webpack')
const LodashModule = require("lodash-webpack-plugin");

module.exports = {
    mode: 'development', // production
    entry: {
        main: './index.js',
        editor: './editor.js',
    },
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: '[name].bundle.js',
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: "jquery",
            jquery: "jQuery",
            "window.jQuery": "jquery"
        }),
        new LodashModule()
    ],
    module: {
        rules: [
            {
                test: /\.css$/i,
                include: path.resolve(__dirname, 'src'),
                use: ['style-loader', 'css-loader', 'postcss-loader'],
            }
        ],
    },
}
