
const path = require('path');
const webpack = require('webpack');
const LodashModule = require("lodash-webpack-plugin");
const glob = require('glob');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const stylesHandler = MiniCssExtractPlugin.loader;

const buildinEntry = () => {

    return glob.sync("include/*/src/main.{js,ts,tsx}", {
        ignore: ['**/node_modules/**', '**/dist/**', '**/.git/**', '**/.vscode/**', '**/.vscode-test/**', '**/vendor/**', '**/tests/**'], // Ignore node_modules directory
        // nodir: true, // Treat directories as files
        // maxDepth: 2 // Limit the depth of the search to 2 directory levels
    }).reduce((acc, item) => {

        const file = path.resolve(__dirname, item);
        const name = path.basename(item).replace(/\.[jt]sx?$/, "");

        if (name.endsWith("bundle")) return item;

        acc["./../../../" + (path.dirname(item).split(path.sep).pop()).replace(/src$/, "dist") + "/" + path.basename(file).replace(/\.[jt]sx?$/, "")] = "./" + path.dirname(item) + "/" + path.basename(item).replace(/\.[jt]sx?$/, "");
        return acc;

    }, {});
};


const entry = () => {
    return {
        ...buildinEntry()
    }
}

module.exports = {
    mode: 'development',
    entry: entry(),
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, "./include/buildin/dist")
    },

    plugins: [
        new MiniCssExtractPlugin(),
        new webpack.ProvidePlugin({
            $: "jquery",
            jquery: "jQuery",
            "window.jQuery": "jquery"
        }),
        new LodashModule(),
    ],
    module: {
        rules: [
            {
                test: /\.(js|ts|tsx)$/i,
                exclude: /node_modules/,
                use: "babel-loader"

            },
            {
                test: require.resolve("jquery"),
                use: [{
                    loader: 'expose-loader',
                    options: {
                        exposes: {
                            globalName: '$',
                            override: true,
                        }
                    },
                }]
            },
            {
                test: /\.s[ac]ss$/i,
                use: [stylesHandler, 'css-loader', 'postcss-loader', 'sass-loader'],
            },
            {
                test: /\.css$/i,
                use: [stylesHandler, 'css-loader', 'postcss-loader'],
            },
            {
                test: /\.(eot|svg|ttf|woff|woff2|png|jpg|gif)$/i,
                type: 'asset',
            },
        ]
    },
    resolve: {
        extensions: ['.tsx', '.ts', '.js'],
    },
    watch: true, // Enable watch mode
    watchOptions: {
        ignored: "/node_modules/*", // Exclude node_modules directory from watching
        aggregateTimeout: 300, // Delay before rebuilding (in milliseconds)
        poll: 1000, // Check for changes every second
    }
};
