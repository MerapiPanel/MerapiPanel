const path = require('path')
const webpack = require('webpack')
const LodashModule = require("lodash-webpack-plugin");
const glob = require('glob');

module.exports = {
    mode: 'development',
    entry: () => {

        const entryFiles = glob.sync('./src/base/assets/*.js').reduce((acc, item) => {

            const filename = item.replace(/^.*[\\\/]/, '').replace(/\.js$/, '');
            const path = item.split("/");
            path.pop();
            const name = "../../" + path.join('/') + '/dist/' + filename;
            acc[name] = item;

            return acc;
        }, {});

        const entryModule = glob.sync("./src/module/**/assets/*.js").reduce((acc, item) => {

            const filename = item.replace(/^.*[\\\/]/, '').replace(/\.js$/, '');
            const path = item.split("/");
            path.pop();
            const name = "../../" + path.join('/') + '/dist/' + filename;
            acc[name] = item;

            return acc;
        }, {});

        return Object.assign({}, entryFiles, entryModule);
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, './src/base'),
    },
    // entry: './src/index.js',
    // output: {
    //     path: path.resolve(__dirname, 'dist'),
    //     filename: '[name].bundle.js',
    // },
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
