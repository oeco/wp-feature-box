# WP Feature Box

Create custom featured content boxes and slides inside your WordPress site. Tell stories and your outreach

## Features

- Easy to combine boxes to create slides
- Shortcodes
- Allow users to embed your feature box
- Responsive design
- Multiple custom links and text

## Usage

Install this plugin on your `wp-content/plugins` directory and activate the plugin. **Feature box** will appear on your dashboard menu, click `Add feature item` to create your first!

There are two ways to use your feature box, you can insert on a post by clicking on the post editor button or you can use PHP:

### PHP for single feature box

```php
<?php
$id = 12; // The id of the feature box item
echo get_feature_box($id);
?>
```

### PHP for slider feature box

```php
<?php
$ids = array(12, 14, 16); // List of ids
echo get_feature_box($ids);
?>
```

### Support

Having trouble with WP Feature Box? Go to our [issues page](https://github.com/oeco/wp-feature-box/issues) and we'll help you there!