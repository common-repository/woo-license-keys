# Reviewer Addon (for Wordpress MVC)

[![Latest Stable Version](https://poser.pugx.org/10quality/wpmvc-addon-reviewer/v/stable)](https://packagist.org/packages/10quality/wpmvc-addon-reviewer)
[![Total Downloads](https://poser.pugx.org/10quality/wpmvc-addon-reviewer/downloads)](https://packagist.org/packages/10quality/wpmvc-addon-reviewer)
[![License](https://poser.pugx.org/10quality/wpmvc-addon-reviewer/license)](https://packagist.org/packages/10quality/wpmvc-addon-reviewer)

![Review notice](https://www.10quality.com/wp-content/uploads/2019/12/wpmvc-addon-reminder.jpg)

Add-on for [Wordpress MVC](http://wordpress-mvc.com/).

This addon will show a review notice to an admin user, suggesting them to review the plugin or theme.

The addon will manage responses and reminders to make the notice not as intrusive.

## Install

Run the following composer command at your project's root:

```bash
composer require 10quality/wpmvc-addon-reviewer
```

## Configuration

Add your project's root folder name inside the `paths` settings, like:

```php
    'paths' => [

        'base'          => __DIR__ . '/../',
        'controllers'   => __DIR__ . '/../Controllers/',
        'views'         => __DIR__ . '/../../assets/views/',
        'log'           => WP_CONTENT . '/wpmvc/log',
        'root_folder'   => 'your-plugin-folder-name',

    ],
```

This will enable localization.

Add the following inside the `addons` settings:

```php
    'addons' => [
        'WPMVC\Addons\Reviewer\ReviewerAddon',
    ],
```

This will enable the addon files.

Add an extra `reviewer` settings:

```php
    'reviewer' => [

        // Enables reviewer
        'enabled'       => true,
        // Name to display in notice
        'name'          => 'Project name',
        // Display interval in minutes
        'interval'      => 43200,

    ],
```

| Setting | Type | Description |
| --- | --- | --- |
| **enabled** | `bool` | Enables or disables the reviewer addon. |
| **name** | `string` | Project name that will display inside the notice. |
| **interval** | `int` | Interval in minutes. This is the time the addon will wait until the notice is displayed (for example 43200, will mean that it will display after 30 days). This interval is also used when the user selects to be reminded later. |
| **url** | `string` | *OPTIONAL*, the review URL the addon will redirect the user to. If nothing is present, the addon will asume it is a Wordpress.org review and will build the url using the textdomain set in the `localize` settings. |

Settings using a custom review url:

```php
    'reviewer' => [

        // Enables reviewer
        'enabled'       => true,
        // Name to display in notice
        'name'          => 'Project name',
        // Display interval in minutes
        'interval'      => 43200,
        // Review url
        'url'           => 'https://mydomain.com/my-product/review'

    ],
```

## Wordpress Hooks

### Filter:wpmvc_addon_reviewer_img_{namespace}

`wpmvc_addon_reviewer_img_{namespace}`

Allows to filter and replace the default `stars.svg` display in the notice, for the image of your choice.

Replace **{namespace}** with your Wordpress MVC project's namespace (With caps).

| Parameter | Type | Description |
| --- | --- | --- |
| `$image_url` | `string` | Review image url. Recommended resolution (150px x 150px). |

Usage example (namespace is *TestPlugin*):

```php
add_filter( 'wpmvc_addon_reviewer_img_TestPlugin', function( $url ) {
    // 150px x 150px
    return 'https://www.domain.com/path-to/image.png';
} );
```

## Coding Guidelines

PSR-2 coding guidelines.

# License
GPLv3 License. (c) 2019 [10 Quality](https://www.10quality.com/).