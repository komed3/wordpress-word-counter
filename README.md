# WordPress word counter

WordPress tool to calculate word count in all posts. Fast processing using standard WordPress functions, clear statistics in admin area.

- Version 0.01 [ALPHA]
- Date 12/09/2022

### Installation

Clone the repository to your directory or [download the latest version](https://github.com/komed3/wordpress-word-counter/releases).

```shell
git clone https://github.com/komed3/wordpress-word-counter
```

Upload ``wp-content`` directory to the root of your WordPress installation.

Activate the plugin in your WordPress dashboard. ``Plugins >> Installed Plugins``

Navigate to WordPress tool Word Counter. ``Tools >> Word Counter``

Update word count data by click on ``Refresh``.

### Settings

Essential parameters can be set using global variables in the ``wpwc.php`` file.

```php
$__wpwc_capability = 'manage_options';
```

User capability to access WPWC admin page. See [Roles and Capabilities](https://wordpress.org/support/article/roles-and-capabilities/) for details.

```php
$__wpwc_titles = true;
```

If true, titles are included in the calculation.

```php
$__wpwc_reading_speed = 300;
```

Reading speed in words per minute.
