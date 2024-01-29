const path = require('path');
const webpack = require('webpack');
const LodashModule = require("lodash-webpack-plugin");
const glob = require('glob');


const entry = () => {
    
    const entryFiles = glob.sync('./src/base/assets/*.js').reduce((acc, item) => {
        
        const file = `./${item}`;
        const name = path.basename(file).replace(".js", "");
        acc[name] = file;

        return acc;
    }, {});
    
    const entryModule = glob.sync("./src/module/**/assets/*.js").reduce((acc, item) => {
        
        const directoryPath = path.dirname(item);
        const name = path.basename(item).replace(".js", "");
        const file = `./${item}`;

        acc[`..\\..\\..\\..\\${directoryPath}\\dist\\${name}`] = file; // Use full file path as key

        // console.log(acc)
        return acc;
    }, {});
    

    return { ...entryModule, ...entryFiles };
};


module.exports = {
    mode: 'development',
    entry: entry(),
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, "./src/base/assets/dist"),
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
                // include: path.resolve(__dirname, './src/module/*'),
                use: ['style-loader', 'css-loader', 'postcss-loader'],
            },
            {
                test: /\.scss$/,
                use: [
                    'style-loader', // Injects styles into the DOM using a <style> tag
                    'css-loader',   // Translates CSS into CommonJS
                    'sass-loader'   // Compiles Sass to CSS
                ],
            },
            {
                test: /\.twig$/,
                use: 'twig-loader',
            },
        ]
    },
    watch: true, // Enable watch mode
    watchOptions: {
        ignored: /node_modules/, // Exclude node_modules directory from watching
        aggregateTimeout: 300, // Delay before rebuilding (in milliseconds)
        poll: 1000, // Check for changes every second
    },
};
