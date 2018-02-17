# One-Time Callbacks

[![Build Status](https://travis-ci.org/stevegrunwell/one-time-callbacks.svg?branch=develop)](https://travis-ci.org/stevegrunwell/one-time-callbacks)
[![Coverage Status](https://coveralls.io/repos/github/stevegrunwell/one-time-callbacks/badge.svg?branch=develop)](https://coveralls.io/github/stevegrunwell/one-time-callbacks?branch=develop)
[![GitHub release](https://img.shields.io/github/release/stevegrunwell/one-time-callbacks.svg)](https://github.com/stevegrunwell/one-time-callbacks/releases)

The [The WordPress plugin API](https://codex.wordpress.org/Plugin_API) is a fantastic way for third-party scripts to be able to inject themselves into the WordPress lifecycle. Thanks to WordPress actions and filters (collectively "hooks"), theme and plugin developers can introduce all sorts of new functionality.

Occasionally, however, the "all or nothing" mentality of WordPress hooks can put developers in a pinch, since they _only_ want their callback to run once. For example, maybe your theme has a simple `add_top_story_class()` function, which appends `.top-story` to a list of classes. If you only wanted to apply it to the first post in a loop, you might find yourself writing code like this:

```php
<?php while ( $query->have_posts() ) : $query->the_post(); ?>

  <?php if ( 0 === $query->current_post ) add_filter( 'post_class', 'add_top_story_class' ); ?>

  <article <?php post_class(); ?>>
    <h2><?php the_title(); ?></h2>
    <?php the_excerpt(); ?>
  </article>

  <?php remove_filter( 'post_class', 'add_top_story_class' ); ?>

<?php endwhile; ?>
```

Yuck! We're conditionally adding a filter based on the current post's index, then removing the filter at the end to ensure it isn't getting applied to every post.

With **One-time callbacks**, this becomes much cleaner:

```php
<?php while ( $query->have_posts() ) : $query->the_post(); ?>

  <?php add_filter_once( 'post_class', 'add_top_story_class' ); ?>

  <article <?php post_class(); ?>>
    <h2><?php the_title(); ?></h2>
    <?php the_excerpt(); ?>
  </article>

<?php endwhile; ?>
```

The `add_filter_once()` function will let the callback execute exactly one time, then it will automatically clean up after itself. It's a small but helpful tool for themes and plugins that make heavy use of actions and filters.

## Installation

The best way to install this package is [via Composer](https://getcomposer.org/):

```sh
$ composer require stevegrunwell/one-time-callbacks
```

The package ships with the [`composer/installers` package](https://github.com/composer/installers), enabling you to control where you'd like the package to be installed. For example, if you're using One-time Hooks in a WordPress plugin, you might store the file in an `includes/` directory. To accomplish this, add the following to your plugin's `composer.json` file:

```json
{
    "extra": {
        "installer-paths": {
            "includes/{$name}/": ["stevegrunwell/one-time-callbacks"]
        }
    }
}
```

Then, from within your plugin, simply include or require the file:

```php
require_once __DIR__ . '/includes/one-time-callbacks/one-time-callbacks.php';
```

### Using as a plugin

If you'd prefer, the package also includes the necessary file headers to be used as a WordPress plugin.

After downloading or cloning the package, move `one-time-callbacks.php` into either your `wp-content/mu-plugins/` (preferred) or `wp-content/plugins/` directory. If you chose the regular plugins directory, you'll need to activate the plugin manually via the Plugins &rsaquo; Installed Plugins page within WP Admin.

### Bundling within a plugin or theme

One-time Callbacks has been built in a way that it can be easily bundled within a WordPress plugin or theme, even commercially.

Each function declaration is wrapped in appropriate `function_exists()` checks, ensuring that multiple copies of the library can co-exist in the same WordPress environment.

## Usage

One-time Callbacks provides the following functions for WordPress:

* [`add_action_once()`](#add_action_once)
* [`add_filter_once()`](#add_filter_once)

### add_action_once()

Register an action to run exactly one time.

The arguments match that of [`add_action()`](https://developer.wordpress.org/reference/functions/add_action/), but this function will also register a second callback designed to remove the first immediately after it runs.

#### Parameters

<dl>
    <dt>(string) $hook</dt>
    <dd>The action name.</dd>
    <dt>(callable) $callback</dt>
    <dd>The callback function.</dd>
    <dt>(int) $priority</dt>
    <dd>Optional. The priority at which the callback should be executed. Default is 10.</dd>
    <dt>(int) $args</dt>
    <dd>Optional. The number of arguments expected by the callback function. Default is 1.</dd>
</dl>

#### Return value

Like `add_action()`, this function always returns `true`.

### add_filter_once()

Register a filter to run exactly one time.

The arguments match that of [`add_filter()`](https://developer.wordpress.org/reference/functions/add_filter/), but this function will also register a second callback designed to remove the first immediately after it runs.

#### Parameters

<dl>
    <dt>(string) $hook</dt>
    <dd>The action name.</dd>
    <dt>(callable) $callback</dt>
    <dd>The callback function.</dd>
    <dt>(int) $priority</dt>
    <dd>Optional. The priority at which the callback should be executed. Default is 10.</dd>
    <dt>(int) $args</dt>
    <dd>Optional. The number of arguments expected by the callback function. Default is 1.</dd>
</dl>

#### Return value

Like `add_filter()`, this function always returns `true`.

## License

Copyright 2018 Steve Grunwell

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
