/**
 * Webpack 配置
 */

'use strict';

var fs = require('fs');
var path = require('path');
var srcDir = path.resolve(process.cwd(), 'src');
var assetsDir = path.resolve(process.cwd(), 'assets');
var webpack = require('webpack');
var commonsPlugin = new webpack.optimize.CommonsChunkPlugin('common.js'); // 将多个入口文件的公用部分提取为common.js

function getEntry () {
    var jsPath = path.resolve(srcDir, 'js');
    var dirs = fs.readdirSync(jsPath);
    var matchs = [], files = {};
    dirs.forEach(function (item) {
        matchs = item.match(/(.+)\.js$/);
        if (matchs) {
            files[matchs[1]] = './src/js/' + item;
        }
    });
    return files;
}

// @see https://github.com/fwon/blog/issues/17
module.exports = {
    cache: true,
    // devtool: 'cheap-module-eval-source-map',
    entry: getEntry(),
    output: {
        path: path.join(assetsDir, '/js/'),
        publicPath: 'assets/js/',				// 用于配置文件发布路径，如CDN或本地服务器
        filename: '[name].js'
    },
    module: {
        // 各种加载器，即让各种文件格式可用require引用
        // 这里只让webpack做js的处理，其他交给gulp，所以只用js的loader
        loaders: [{
            test: /\.js$/,
            loader: 'babel-loader',
            exclude: 'node_modules',
            query: {
                presets: ['es2015']
            }
        }]
    },
    resolve: {
        modulesDirectories: ['node_modules', './src'],
        // 配置别名，在项目中可缩减引用路径
        alias: {},
        extensions: ['', '.js']
    },
    plugins: [
        // 提供全局的变量，在模块中使用无需用require引入
        new webpack.ProvidePlugin({
            jQuery: 'jquery',
            $: 'jquery',
            TT: 'tt'
        }),
        // 压缩
        // new webpack.optimize.UglifyJsPlugin({
        //     compress: {
        //         warnings: false
        //     }
        // }),
        // 将公共代码抽离出来合并为一个文件
        commonsPlugin
    ],
    externals: {
        'jquery': 'jQuery',
        'tt': 'TT'
    }
};
