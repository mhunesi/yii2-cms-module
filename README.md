# yii2-cms-module

A CMS & routing module for Yii 2 applications.

Features:

* content version history & sandbox mode
* handles routing on top of built-in `urlManager`
* supports multiple websites
* sitemap-aware

## Installation via Composer

You can install package with a command:

> composer require webkadabra/yii2-cms-module

This module is compatible with the following yii-2 extensions:

* `omgdef\multilingual`
* `dektrium\user`

## Setup

### Requirements

* Your application must have `application\components\Controller` component

### Configuration

Add `cmsRouter` component to your main application configuration (e.g. `config/main.php` or `frontend/config/main.php`, 
depending on your application structure):

```
// ...
'components' => [
        // ...
        'cmsRouter' =>[
            'class' => 'webkadabra\yii\modules\cms\components\CmsRouter',
            'containerAppCode' => null, // default
        ],
        // ...
],
// ...
```

Add `cmsRouter` component to `bootstrap` property at your main application config (usually, the same file as in previous step):

```
// ...
    'bootstrap' => ['log',  'cmsRouter', /* ... other conponents ... */],
],
// ...
```

Add `cms-web` module to your main (web) application:

```
// ...
'modules' => [
        // ...
        'cms-web' => 'webkadabra\yii\modules\cms\Module',
        // ...
],
// ...
```

Add `cms` module to your admin or backend application (it can be the same application config):

```
// ...
'modules' => [
        // ...
        'cms' => [
            'class' => 'webkadabra\yii\modules\cms\AdminModule',
            'availableControllerRoutes' => [
                'site' => [
                    'label' => 'Global',
                    'actions' => [
                        'index' => 'Home page',
                        'contact' => 'Contact page',
                        'page' => [
                            'label' => 'Static page',
                            'params' => [
                                ['view' => 'File name'],
                            ],
                            #'params'=>['id', 'dummy'], // For multiple params
                        ],
                    ],
                ],
                'rss'=>[
                    'label' => 'RSS feeds controller',
                    'actions'=>[
                        'view'=>array(
                            'label'=>'Display feed',
                            'params'=>['id'], // Required or available action params
                        ],
                    ],
                ],
                'store' => array(
                    'label' => 'Store',
                    'actions' => array(
                        'index' => 'Store front page',
                        'good' => [
                            'label' => 'Offer page',
                            'params' => [
                                ['alias' => 'Offer code (alias or SKU)'],
                            ],
                        ],
                        'listCategory' => [
                            'label' => 'Offers in a category',
                            'params' => [
                                ['alias' => 'Category code (alias)'],
                            ],
                        ),
                        'promotion' => [
                            'label' => 'Promo page',
                            'params' => [
                                ['promoid' => 'Promotion ID'],
                                ['promoName' => 'Promotion name'],
                            ],
                        ],
                    ],
                ],
            ]
        ],
        // ...
],
// ...
```

(optional) Add module migrations folder to your console application config file (or main config file, if you're not using 
advanced application structure):

```
// ...
'controllerMap' => [
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationNamespaces' => [
            'vendor/webkadabra/yii2-cms-module/migrations',
            // ... other modules' migrations, if any
        ],
    ],
],
// ...
```

... and then run `php yii migrate` command. If you choose not to add module's migrations path to your config,
you can setup database by running migrations directly:

> $ php yii migrate --migrationPath=@vendor/webkadabra/yii2-cms-module/migrations

NOTICE: you will have to run this command every time there is a change in modules' migrations.

Thanks, pull requests and donations are welcome!

- Sergii
