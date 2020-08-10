var mix = require('webpack-mix').mix;
var compressor = require('node-minify');
var Promises = require('promise');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

var promise = new Promises(function (resolve, reject) {
    mix.copy('public/assets/fonts', 'public/frontend/fonts', false);
    mix.copy('public/assets/images', 'public/frontend/images', false);
    mix.copy('public/assets/plugins', 'public/frontend/plugins', false);
    mix.copy('public/assets/js', 'public/frontend/js', false);
});

Promise.all([
  Promise.resolve(
    mix.styles([
        'public/assets/css/bootstrap.min.css',
        'public/assets/css/font-awesome.css',
        'public/assets/css/myfont.css',
        'public/assets/css/owl.carousel.min.css',
        'public/assets/css/owl.theme.default.min.css',
        'public/assets/css/toastr.min.css',
        'public/assets/css/ladda-themeless.min.css',
        'public/assets/css/jquery.fancybox.css',
        // 'public/ajaxupload/css/jquery.fileupload.css',
        'public/assets/css/jquery.mobile-menu.css',
        'public/assets/css/marquee.css',
        'public/assets/plugins/jquery-autocomplete/jquery.auto-complete.css',
        'public/assets/css/bootstrap-datetimepicker.min.css',
        'public/assets/css/main.css'
    ], 'public/frontend/css/all.css')
  ),
  Promise.resolve(
    mix.scripts([
        'public/assets/js/bootstrap.min.js',
        'public/assets/js/owl.carousel.min.js',
        'public/assets/js/toastr.min.js',
        'public/assets/js/spin.min.js',
        'public/assets/js/ladda.min.js',
        // 'public/assets/js/jquery.fancybox.pack.js',
        'public/assets/js/ajaxupload.js',
        'public/assets/js/jquery.mobile-menu.min.js',
        'public/assets/plugins/jquery-autocomplete/jquery.auto-complete.min.js',
        'public/assets/js/bootstrap-datetimepicker.min.js',
        'public/assets/js/jquery.lazy.js',
        'public/assets/js/script.js',
    ], 'public/frontend/js/all.js')
  )
]).then(function (res) {
    compressor.minify({
      compressor: 'gcc',
      input: 'public/frontend/js/all.js',
      output: 'public/frontend/js/all.min.js',
      callback: function(err, min) {
        console.log('Wildcards JS GCC');
      }
    });
    compressor.minify({
      compressor: 'yui',
      input: 'public/frontend/css/all.css',
      output: 'public/frontend/css/all.min.css',
      type: 'css',
      callback: function(err, min) {
        console.log('YUI CSS one file');
      }
    })
})

// mix.js('src/app.js', 'dist/').sass('src/app.scss', 'dist/');

// Full API
// mix.js(src, output);
// mix.react(src, output); <-- Identical to mix.js(), but registers React Babel compilation.
// mix.preact(src, output); <-- Identical to mix.js(), but registers Preact compilation.
// mix.coffee(src, output); <-- Identical to mix.js(), but registers CoffeeScript compilation.
// mix.ts(src, output); <-- TypeScript support. Requires tsconfig.json to exist in the same folder as webpack.mix.js
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.standaloneSass('src', output); <-- Faster, but isolated from Webpack.
// mix.fastSass('src', output); <-- Alias for mix.standaloneSass().
// mix.less(src, output);
// mix.stylus(src, output);
// mix.postCss(src, output, [require('postcss-some-plugin')()]);
// mix.browserSync('my-site.test');
// mix.combine(files, destination);
// mix.babel(files, destination); <-- Identical to mix.combine(), but also includes Babel compilation.
// mix.copy(from, to);
// mix.copyDirectory(fromDir, toDir);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setPublicPath('path/to/public');
// mix.setResourceRoot('prefix/for/resource/locators');
// mix.autoload({}); <-- Will be passed to Webpack's ProvidePlugin.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
// mix.babelConfig({}); <-- Merge extra Babel configuration (plugins, etc.) with Mix's default.
// mix.then(function () {}) <-- Will be triggered each time Webpack finishes building.
// mix.extend(name, handler) <-- Extend Mix's API with your own components.
// mix.options({
//   processCssUrls: true, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
//   purifyCss: false, // Remove unused CSS selectors.
//   uglify: {}, // Uglify-specific options. https://webpack.github.io/docs/list-of-plugins.html#uglifyjsplugin
//   postCss: [] // Post-CSS options: https://github.com/postcss/postcss/blob/master/docs/plugins.md
// });