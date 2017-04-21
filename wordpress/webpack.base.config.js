const webpack = require('webpack');
const path = require('path');
const ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = {
    context: path.resolve(__dirname, './src'),
    entry: {
        app: "./scripts/main.js",
        backend: "./scripts/backend.js"
    },
    output: {
        path: path.resolve(__dirname, './crinisbans/dist'),
        filename: '[name].bundle.js',
    },
    module: {
        rules: [
            { 
                test: /\.(sass|scss)$/,
                use: ExtractTextPlugin.extract({
                    fallback: "style-loader",
                    use: ["css-loader","postcss-loader","sass-loader"],
                })
            },
            { test: /\.js$/, exclude: /node_modules/, use: "babel-loader" },
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: {
                        'scss': ExtractTextPlugin.extract({
                                    fallback: ["vue-style-loader"],
                                    use: ["css-loader","postcss-loader","sass-loader"],
                                })
                    }
                }
            },
            { test: /\.(png|jpg)$/, use: 'file-loader?name=[name].[ext]&outputPath=/img/' },
            {
                test: /\.(ttf|otf|eot|svg|woff(2)?)(\?[a-z0-9]+)?$/,
                loader: 'file-loader?name=fonts/[name].[ext]'
            },
        ]
    },
    plugins: [
        new ExtractTextPlugin("styles.css"),
    ],

};