# WordPress Bootstrap

Superbia's WordPress starter project.

## Features

* Better folder structure
* Dependency management with [Composer](http://getcomposer.org)
* Environment specific WordPress configuration
* WordPress Extra coding standards with [WP Enforcer](https://github.com/stevegrunwell/wp-enforcer)
* Browser targeting with [Browserslist](https://github.com/browserslist/browserslist)

## Requirements
* [Git](https://git-scm.com)
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
* [Yarn](https://yarnpkg.com/en/docs/install)
* PHP >= 5.6+

## Guided Installation

Clone this repo and then run the following command to create your new project:

```bash
$ ./bin/bootstrap
```

You will be prompted for the following information:
* Theme name [My Awesome Theme]
* Domain name [awesome.com]
* If you'd like to remove the bootstrap git repo and init a new one"

The bootstrap script will then take care of all the steps under *Manual Installation*
* [Rename theme](#rename-theme)
* [Install dependencies](#install-dependencies)
* [Install WP Enforcer pre commit hook](#wordPress-enforcer)
* [Theme setup](#theme-setup)

## Manual Installation

### Rename theme

The first thing you need to do is renmae the _s theme directory, changing it to something else (like, say, my-awesome-theme), and then you'll need to do a five-step find and replace on the name in all of the templates.
1. Search for: '_s' and replace with: 'my-awesome-theme'
2. Search for: _s_ and replace with: my_awesome_theme_
3. Search for: Text Domain: _s and replace with: Text Domain: my-awesome-theme in style.css.
4. Search for:  _s and replace with:  My_Awesome_Theme
5. Search for: _s- and replace with: my-awesome-theme-

Then, update the stylesheet header in style.css

### Install dependencies

Install dependencies with Composer:

```bash
$ composer install
```
Copy the `config.sample.php` file, replacing `sample` with your environment: `dev`, `staging` or `production`.

### WordPress Enforcer

WP Enforcer uses [Git Hooks](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks) to automatically run PHP_CodeSniffer every time a developer tries to make a commit. If the code doesn't conform to the WordPress VIP coding standards, the commit will be rejected and the developer will get a message explaining what needs to be fixed.

Run the WP Enforcer installation script to copy the Git hooks into your local repository:

```bash
$ ./vendor/bin/wp-enforcer
```

### Theme setup

1. `cd public/app/theme/neoskosmos`.
2. `yarn` to install required dependencies.
3. `gulp` to run all the tasks required to build the theme.
4. `gulp watch` to watch for changes and automatically run tasks.
5. Visit `http://localhost:3000` to view live-reloaded changes via Browsersync.
6. `yarn run gulp-build` to build the theme's assets for production.

## Usage

### PHPCS

If your commit is rejected, you can run PHP_CodeSniffer manually while working through any issues:

```bash
$ ./vendor/bin/phpcs
```

### Browserslist

Browserslist allows you to set target browsers to support in your package.json file and share the config with tools like Autoprefixer and Babel. Unfortunately it isn't supported in version 6 of Babel.

WordPress Bootstrap currently uses the following config:

```json
"browserslist": [
  "> 0.5%",
  "last 2 version",
  "not dead",
  "IE 10"
]
```

Browserslist has some helpful commands that you can use to see what browsers are being supported by your config.

List Browsers
```bash
$ yarn run browserslist
```

List coverage
```bash
$ yarn run browserslist --coverage
```

## Credits

Thanks to the following projects/people that have influenced our WordPress Bootstrap

* [Roots Bedrock](https://github.com/roots/bedrock) for folder structure and composer setup
* Rarst for his [Guide to Composer in WordPress](https://composer.rarst.net/)
* [Underscores](https://github.com/Automattic/_s) for their theme renaming strategy
* Focus Lab's [EE Master Config](https://github.com/focuslabllc/ee-master-config) and Ashfame's [awesome-wp-config-file.php](https://gist.github.com/ashfame/1923821)
