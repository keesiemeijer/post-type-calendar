# Post Type Calendar

Version:           1.0.0  
Requires at least: 4.6  
Tested up to:      4.8  

A WordPress plugin to display a calendar with post titles.

This plugin uses the [SimpleCalendar](https://github.com/donatj/SimpleCalendar) by donatj to display the calendar.

## Installation ##
Clone this repository inside the WordPress plugins directory
```bash
git clone https://github.com/keesiemeijer/post-type-calendar.git
```
And activate the plugin. 

Now you can use the [calendar functions](https://github.com/keesiemeijer/post-type-calendar/#calendar-functions) in your theme to display a calendar with your post titles

## Composer ##

All packages are included in the plugin itself (instead of the `/vendor` directory) by making use of [Mozart](https://github.com/coenjacobs/mozart) by Coen Jacobs. That's why you don't have to use `composer install` when installing this plugin

Mozart installs the packages automatically in the `src/Dependencies` directory after using `composer update` or `composer install`.

## Creating a new build ##
To compile the plugin (without all the development files) use the following commands:
```bash
# Install Grunt tasks
npm install

# Build the production plugin
grunt build
```
The plugin will be compiled in the `build` directory.

## Calendar Functions ##

### Function: get_calendar

```php
function get_calendar( $posts [, $args = '' ])
```

Returns calendar html

#### Parameters:

- ***array*** `$posts` - Array with post objects to show in the calendar.
- ***array*** | ***string*** `$args` - Optional calendar arguments
    - ***int*** `start_of_week` - Day to start on, 0-6 where 0 is Sunday.
    - ***string*** `linkto` -  Link to date archives or posts. Accepts: `date_archives`, `posts` or empty string. Default: `date_archives`
    - ***string*** `day_names` - Type of weekday names. Accepts: 'abbreviation', 'weekday' or 'initial'.Default 'abbreviation'.

#### Example
```php
<?php
// Get posts to show in calendar.
$args = array(
	'year'  => 2017,
	'month' => 6,
);

$calendar_posts = get_posts( $args );

// display calendar and link to posts
echo \keesiemeijer\Post_Type_Calendar\get_calendar( $calendar_posts, array( 'linkto' => 'posts' ) );
?>
```

---
