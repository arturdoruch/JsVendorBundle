# JsVendorBundle
Popular JavaScript libraries in one bundle.

## Installation

Via composer. Add the following lines to your main composer.json file, "scripts" and "repositories" block.
```json
"scripts": {
    "post-install-cmd": [
        ...
        "ArturDoruch\\JsVendorBundle\\Composer\\ScriptHandler::installJsVendor"
    ],
    "post-update-cmd": [
        ...
        "ArturDoruch\\JsVendorBundle\\Composer\\ScriptHandler::installJsVendor"
    ]
},
"repositories": [
    ...
    {
        "type": "vcs",
        "url": "https://github.com/arturdoruch/BootstrapBundle"
    },
    {
        "type": "vcs",
        "url": "https://github.com/arturdoruch/JsVendorBundle"
    }
]
```

Install bundle by running cli command.

```composer require "arturdoruch/js-vendor-bundle"```

Add ArturDoruchJsVendorBundle to your application kernel

```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new ArturDoruch\JsVendorBundle\ArturDoruchJsVendorBundle()
    );
}
```

Install assets

```console assets:install --symlink```
