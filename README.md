# flade team WordPress starter theme

Our default WP theme.

* [Plugins](#plugins)
* [Namespacing in PHP and JavaScript](#namespacing-in-php-and-javascript)
* [Fonts](#fonts)
* [Styles and scripts in theme](#styles-and-scripts-in-theme)
* [Blocks development](#blocks-development)
* [How to use PHPCS in theme](#how-to-use-phpcs-in-theme)
* [Pagespeed](#pagespeed)

## Update theme version

- themes/flade/style.css
- themes/flade/package.json

## Plugins

All our plugins from WordPress directory we should add in the composer.json in a required section, example

```
"require": {
    "wpackagist-plugin/password-protected":"*",
    "wpackagist-plugin/query-monitor":"*",
    "advanced-custom-fields/advanced-custom-fields-pro":"6.0.*",
    "wpackagist-plugin/contact-form-7":"5.6.3",
    "wpackagist-plugin/contact-form-cfdb7":"1.2.*"
}
```

After when all plugins are written, we need to install composer dependencies

Open your terminal in the project root and type `composer install`

## Namespacing in PHP and JavaScript

We need to always use namespacing in PHP files. In PHP, we will use `fladeTheme`.

## Fonts

All fonts should be placed locally in the `themes/flade/src/fonts/` folder in `woff2` format and connected
in `themes/flade/src/styles/base/_fonts.scss`. Fonts should be grouped by families and placed intro variables
here `themes/flade/src/styles/helpers/_vars.scss`. Main font is used globally, and second is preferred to use with a
predefined class `.ff-second`.
To improve CLS (pagespeed), don't forget to add theme fonts in preload function `preload_font`.

## Styles and scripts in theme

Open your terminal

Go to the path `cd themes/flade`

Type `npm install`

When modules are installed, need to run npm command `npm run start`.
Watcher automatically rebuilds all css and scripts in theme

After that, we can write styles and js scripts

## Styles

We have a default layout classes that should be used for a theme developing, please check this
folder `themes/flade/src/styles/base/` and update it according to theme design (font-size, wrapper width,
headlines, etc.).

Theme scss helpers (vars, mixins, etc.) can be found and updated here `themes/flade/src/styles/helpers/`
Any styles should be placed in `themes/flade/src/styles`.

When you write your `scss` code you need to try to break up your code into multiple logical files.

For best CWV, we're using inline CSS for critical styles and every unique section (not a Gutenberg block).
Every scss file, placed in `themes/flade/src/styles/inline/`, will be automatically compiled into separate css file.
There is a function `flade_inline_style( '$filename' )` that will output generated styles to HTML.

For example:

```
// themes/flade/src/styles/main.scss

@import 'helpers';
@import 'base/reset';
@import 'base/fonts';
@import 'base/framework';
@import 'base/layout';
@import 'ui';
@import 'base/content-frontend';
@import 'sections';
```

## SVG icons

All required for theme SVG images should be placed into `themes/flade/src/sprite` folder, so they will be
automatically compiled into `themes/flade/build/sprite.svg` file.
To use this sprite, we have php helper `flade_the_sprite_icon()`.
Fos SCSS that sprite is incompatible, so please use it with PHP only.
If some icon can't be compiled into sprite properly, it should be placed into `static` folder and used separately.

For example:

```
flade_the_sprite_icon( 'arrow-right', [24, 12], 'icon-extra-classname' );
```

## Static media files

Images/videos that are not used in scss but are needed for PHP markup should be placed in
the `themes/flade/static` folder.

## Scripts

Any scripts should be placed in `themes/flade/src/scripts`. When you write your `javascript` code you need to try to
break up your code into multiple logical files. All scripts should be imported into
`themes/flade/src/(entry|front-delayed).js`. File `entry.js` should contain only important scripts, that must be
fired without the delay.

For example:

```
// themes/flade/src/entry.js

import './scripts/very-imporant-functions-file'


// themes/flade/src/front-delayed.js

import './scripts/slider-js-file'
```

## Blocks development

When you create a new block, you need to duplicate folder `themes/flade/src/blocks/example` and write a new your own
block based on sample block. Custom blocks activation is implemented in `themes/flade/includes/blocks.php`
by `register_theme_blocks()` function with a `$blocks` array.

There is also an advanced block example and a "remove confirmation" component for global use.

Write block styles to the `block.scss`. They will be automatically imported to `editor.scss` (with additional classnames
folding to override default WP styles) and to `style.scss`. This structure is more flexible and giving the possibility
to use different mixins/functions with a same names for frontend and backend (e.g. `inREM()`).

Block scripts can be added to:

- `script.js` (enqueued on frontend and editor)
- `front.js` (frontend only)
- `editor.js` (editor only)

## How to use PHPCS in theme

https://flade.slab.com/posts/%D0%BD%D0%B0%D1%81%D1%82%D1%80%D0%BE%D0%B9%D0%BA%D0%B0-php-storm-doq8nio2

## Pagespeed

Our theme is written for the best results. The WP-Rocket plugin is the most useful for now.
With the following options, it will help improve pagespeed even further:

- Cache:
    - Enable caching for mobile devices
        - Separate cache files for mobile devices
- File Optimization
    - CSS Files
        - Minify CSS files
        - Optimize CSS delivery
            - Load CSS asynchronously
    - JavaScript Files
        - Minify JavaScript files
        - Load JavaScript deferred
        - Delay JavaScript execution
            - Excluded JavaScript Files
                - `flade/build/front.js`
                - `window.dataLayer`
                - `googletagmanager.com`
- Media
    - LazyLoad
        - Enable for images
        - Enable for iframes and videos
            - Replace YouTube iframe with preview image
    - Image Dimensions
        - Add missing image dimensions
- Preload
    - Preload Cache
        - Activate Preloading


## Tests
- Folder: themes/flade/tests
- Generate a new template for a test
  - `composer generate-test TestName`
- How to run a test
  - For example we have a test with the name TrimStringTest which is located in tests\TrimStringTest.php. We just need to run this command in a command line: ` ./vendor/bin/phpunit themes/flade/tests/TrimStringTest.php
    `
