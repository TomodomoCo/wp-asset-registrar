# WP Asset Registrar

WP Asset Registrar is a simple library that makes the process of registering and enqueueing assets in WordPress slightly more intuitive.

## Usage

```php
<?php

use Tomodomo\WpAssetRegistrar\Registrar;

$registrar = new Registrar();

add_action('wp_enqueue_scripts', function () use ($registrar) {
    $registrar->addScript('my-js-libs', '/assets/js/script.js', [
        'dependencies' => [
            'jquery',
        ],
    ]);

    $registrar->addScript('my-js', '/assets/js/script.js', [
        'dependencies' => [
            'my-js-libs',
        ],
    ]);

    $registrar->addStyle('my-css', '/assets/css/style.css');
});

add_action('wp_enqueue_scripts', [$registrar, 'enqueueScripts']);
add_action('wp_enqueue_scripts', [$registrar, 'enqueueStyles']);
```

## About Tomodomo

Tomodomo is a creative agency for magazine publishers. We use custom design and technology to speed up your editorial workflow, engage your readers, and build sustainable subscription revenue for your business.

Learn more at [tomodomo.co](https://tomodomo.co) or email us: [hello@tomodomo.co](mailto:hello@tomodomo.co)

## License & Conduct

This project is licensed under the terms of the MIT License, included in `LICENSE.md`.

All open source Tomodomo projects follow a strict code of conduct, included in `CODEOFCONDUCT.md`. We ask that all contributors adhere to the standards and guidelines in that document.

Thank you!
