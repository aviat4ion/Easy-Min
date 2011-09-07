# Easy Min #

A simple set of minifying scripts for CSS and Javascript

## Basic Use: ##

1. Figure out your file paths, and set them in css.php and js.php.
2. Add your css and javascript files to groups, in `config/css_groups.php` and `config/js_groups.php` respectively
3. Point your CSS links in your HTML to `css.php/g/[group_name]`, and likewise your javascript to `js.php/g/[group_name]`
4. Add a folder named "cache" to your js path
5. Enjoy a faster loading website