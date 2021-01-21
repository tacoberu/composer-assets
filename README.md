Composer Assets
===============

Copies files from vendor to www directory. The list of files is stored in a json file.

Installation
------------

The recommended way to install is via Composer:

        composer require tacoberu/composer-assets



Usage
-----

```json
    {
    	"require": {
    		"tacoberu/composer-assets": "*",
    	},
        "scripts": {
            "post-autoload-dump": [
                "Taco\\ComposerScripts\\CopyAssetsToPublic::process"
            ]
        },
        "config": {
    		"www-dir": "public",
    		"assets-definition": "scripts/assets.json"
    	}
    }
```

Add list of files definition (vendor-src -> public-desc) in scripts/assets.json:
```json
{
	"nette/forms/src/assets/netteForms.js": "assets/js/netteForms.js",
	"nette/forms/examples/assets/logo.png": "assets/img/logo.png",
	"../app/examples/assets/style-1.css": "assets/style.css",
	"../app/examples/assets/style-2.css": "assets/style.css",
	"../app/examples/assets/style-3.css": "assets/style.css"
}
```

And run:

        composer install
