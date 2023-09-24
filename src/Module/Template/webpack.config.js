const path = require('path')
const webpack = require('webpack')
const LodashModule = require("lodash-webpack-plugin");

module.exports = {
    mode: 'development', // production
    entry: './index.js',
    output: {
        path: path.resolve(__dirname, 'assets/dist'),
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
