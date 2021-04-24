const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 | mix 管理前端代码。如果需要修改webpack.mix.js 则需要修改ctrl+c 后重新载入才能修改
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 | 有一个问题.如果遇到报错。直接删除node-modules 重新下来
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    // .sourceMaps()
    .version()
;
