/**
 * Webpack 配置
 */

var path = require("path");
var webpack = require('webpack');
//var commonsPlugin = new webpack.optimize.CommonsChunkPlugin('common.js'); //将多个入口文件的公用部分提取为common.js

module.exports = {
    //plugins: [commonsPlugin],
    resolve: {
        root: [path.dirname(process.cwd()) + '/src', path.dirname(process.cwd()) + '/node_modules'],
        alias: {},
        extensions: ['', '.js']
    },

    entry: {
        main: './src/js/main.js'
    },

    output: {
        //path: path.dirname() + '/dist/scripts',
        filename: '[name].js'
        //publicPath: "/dist/scripts/"				//html引用路径，在这里是本地地址
    },

    module: {
        loaders: [{
            test: /\.js$/,
            loader: 'babel-loader',
            exclude: /node_modules/
            //query: {
            //    presets: ['es2015']
            //}
        }]
    },

    externals: {
        'jquery': 'jQuery'
    }
};