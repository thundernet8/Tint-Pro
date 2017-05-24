'use strict';
/*
 * Gulp配置
 */

var gulp = require('gulp');
var pkg = require('./package.json');

// 加载所有 gulp 插件, 插件以 GP 的属性方式调用
// var GP = require('gulp-load-plugins')();

// 逐个加载插件
var cache = require('gulp-cache'),
    clean = require('gulp-clean'),
    // concat = require('gulp-concat'),
    cssmin = require('gulp-cssmin'),
    header = require('gulp-header'),
    // htmlmin = require('gulp-htmlmin'),
    imagemin = require('gulp-imagemin'),
    eslint = require('gulp-eslint'),
    less = require('gulp-less'),
    rename = require('gulp-rename'),
    rev = require('gulp-rev'),
    revCollector = require('gulp-rev-collector'),
    uglify = require('gulp-uglify'),
    webpack = require('gulp-webpack'),
    // notify = require('gulp-notify');
    str2hex = require('gulp-str2hex');

/**
 * 构建文件的注释头
 */
var banner = ['/**',
    ' * Generated on <%= (new Date()).toString()%> by <%= pkg.author %>',
    ' *',
    ' * @package   <%= pkg.capitalName %>',
    ' * @version   v<%= pkg.version %>',
    ' * @author    <%= pkg.author %> <<%= pkg.email %>>',
    ' * @site      <%= pkg.siteAbbr %> <<%= pkg.site %>>',
    ' * @copyright Copyright (c) 2014-<%= (new Date()).getFullYear().toString() %>, <%= pkg.author %>',
    ' * @license   <%= pkg.licenseDes %>',
    ' * @link      <%= pkg.homepage %>',
    ' *',
    '**/',
    ' ',
    ''].join('\n');

/**
 * gulp 任务流
 */

// less 样式文件压缩发布
gulp.task('less', function () {
    // TODO: base64 small images
    console.log('start less task');
    gulp.src('./assets/css')
        .pipe(clean());
    gulp.src('./src/css/*.less')
        .pipe(less())                               // - less 文件预处理
        .pipe(cssmin())                             // - css 文件压缩
        .pipe(rev())                                // - 文件名加MD5后缀
        .pipe(header(banner, {pkg: pkg}))           // - 文档添加注释头
        .pipe(gulp.dest('./assets/css'))           // - 输出文件至发布路径
        .pipe(rev.manifest('src/rev/rev-manifest.json', {
            base: process.cwd() + '/src/rev',
            merge: true
        }))                                         // - 生成一个rev-manifest.json
        .pipe(gulp.dest('./src/rev'));              // - 将 rev-manifest.json 保存到 rev 目录内
});

// 图片压缩发布
gulp.task('imagemin', function () {
    console.log('start imagemin task');
    gulp.src('./assets/img')
        .pipe(clean());
    setTimeout(function () {
        gulp.src('./src/img/**/*.{png,jpg,ico}')
            .pipe(cache(
                imagemin({
                    optimizationLevel: 5, // 类型：Number  默认：3  取值范围：0-7（优化等级）
                    progressive: true, // 类型：Boolean 默认：false 无损压缩jpg图片
                    interlaced: true, // 类型：Boolean 默认：false 隔行扫描gif进行渲染
                    multipass: true // 类型：Boolean 默认：false 多次优化svg直到完全优化
                })
            ))
            .pipe(gulp.dest('./assets/img'));
        // .pipe(notify({ message: 'Images task complete' }));
    }, 2000);
});

// js压缩合并发布
gulp.task('scripts', function () {
    console.log('start scripts task');
    console.log(process.cwd());
    gulp.src('./assets/js')
        .pipe(clean());
    gulp.src(['./src/js/*.js'])
        // .pipe(eslint())                                                        // - js代码检查
        // .pipe(eslint.format())
        .pipe(webpack(require('./webpack.config.js')))                            // - webpack打包模块
        //.pipe(uglify())                                                           // - js压缩
        .pipe(str2hex({
            hexall: false,
            placeholdMode: 0,
            compress: true
        }))                                                                       // - 混淆/中文转16进制
        .pipe(rev())                                                              // - 文件名加MD5后缀
        .pipe(header(banner, {pkg: pkg}))                                         // - 文档添加注释头
        .pipe(gulp.dest('./assets/js'))                                           // - 输出文件至发布路径
        .pipe(rev.manifest('src/rev/rev-manifest.json', {
            base: process.cwd() + '/src/rev',
            merge: true
        }))                                                                       // - 生成一个rev-manifest.json
        .pipe(gulp.dest('./src/rev'));                                            // - 将 rev-manifest.json 保存到 rev 目录内
});

// php文件内资源文件引用路径替换 - see build task
// gulp.task('rev', function () {
//     console.log('start rev task');
//     gulp.src(['./src/rev/*.json', './src/php/*.php'])
//         .pipe(revCollector())                       // - 收集rev-manifest.json文件内需要替换版本的文件信息并替换php模板内引用
//         .pipe(gulp.dest('./'));                     // - 输出php文件至视图目录
// });

// 监控
gulp.task('watch', function () {
    console.log('execute watch and auto-combine task');
    gulp.watch('./src/css/*.less', ['less', 'rev']);
    gulp.watch('./src/js/**/*.{js,jsx}', ['scripts', 'rev']);
    gulp.watch('./src/img/**/*.{png,jpg,gif,ico}', ['imagemin']);
});

// PHP文件修改发布
gulp.task('php', function () {
    console.log('start php task');
    gulp.src(['./src/rev/*.json', './src/php/asset.Constant.php'])
        .pipe(revCollector())
        .pipe(gulp.dest('./core/functions'));
});

// 构建
gulp.task('build', ['less', 'scripts', 'imagemin'], function () {
    console.log('start build task');
    setTimeout(function () {
        gulp.run('php');
    }, 5000);
});

// 清理发布目录
gulp.task('cleanDist', function () {
    console.log('start clean dist directory');
    gulp.src('./assets/**/*.*')
        .pipe(clean());
});

// ESlint
gulp.task('lint', function () {
    return gulp.src('src/js/**/*.js')
        .pipe(eslint())
        .pipe(eslint.format());
});

// 默认任务
gulp.task('default', ['watch'], function () {
    console.log('start monitor task');
});

// 发布主题文件至本地服务器路径调试
gulp.task('deploy', function () {
    console.log('Begin clear themes folder');
    gulp.src('D:/Dev/WebServer/WWW/wordpress/wp-content/themes/Tint/')
        .pipe(clean({force: true}));
    setTimeout(function () {
        console.log('Begin copy theme to themes folder');
        gulp.src(['./assets/**/*.*'])
            .pipe(gulp.dest('D:/Dev/WebServer/WWW/wordpress/wp-content/themes/Tint/assets'));
        gulp.src(['./core/**/*.*'])
            .pipe(gulp.dest('D:/Dev/WebServer/WWW/wordpress/wp-content/themes/Tint/core'));
        gulp.src(['./dash/**/*.*'])
            .pipe(gulp.dest('D:/Dev/WebServer/WWW/wordpress/wp-content/themes/Tint/dash'));
        gulp.src(['./*.php', './*.png', './*.css'])
            .pipe(gulp.dest('D:/Dev/WebServer/WWW/wordpress/wp-content/themes/Tint'));
    }, 2000);
});
