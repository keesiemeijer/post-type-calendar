# Post Type Calendar [![Build Status](https://travis-ci.org/keesiemeijer/post-type-calendar.svg?branch=master)](http://travis-ci.org/keesiemeijer/post-type-calendar) #

Version:           1.1.0  
Requires at least: 4.6  
Tested up to:      4.8  

A WordPress plugin to display a calendar with post titles from any post type.

Use [the shortcode](https://github.com/keesiemeijer/post-type-calendar/wiki/Shortcode) `[pt_calendar]` or [functions](https://github.com/keesiemeijer/post-type-calendar/wiki/Functions) to display the calendar where you want.

This plugin uses the [SimpleCalendar](https://github.com/donatj/SimpleCalendar) by donatj to display the calendar.

[documentation](https://github.com/keesiemeijer/post-type-calendar/wiki).
[example calendar](https://github.com/keesiemeijer/post-type-calendar/wiki).

## Installation ##

Clone or download this repository inside the WordPress `plugins` directory
```bash
git clone https://github.com/keesiemeijer/post-type-calendar.git
```
And activate the plugin. 

Now you can use the [calendar functions](https://github.com/keesiemeijer/post-type-calendar/wiki/Functions) in your theme to display a calendar with your post titles.

### requirements ###
This plugin requires PHP 5.6 or greater.

**Note**: This plugin displays an admin notice when activated on lower PHP versions.

## Composer ##

All (third-party) packages are included in the `src/Dependencies` directory by [Mozart](https://github.com/coenjacobs/mozart). Mozart installs the packages there automatically after using `composer update` or `composer install`.

## Creating a new build ##
To compile the plugin (without all the development files) go to the the `post-type-calendar` directory and use the following commands.
```bash
# Install Grunt tasks
npm install

# Build the production plugin
grunt build
```
The plugin will be compiled in the `build` directory.

## Documentation ##
For documentation visit the [Wiki](https://github.com/keesiemeijer/post-type-calendar/wiki)

