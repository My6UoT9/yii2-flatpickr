# Yii2 Flatpickr (fork of mix8872/yii2-flatpickr)

Click on a :star:!

[Flatpickr](https://chmln.github.io/flatpickr/) is a lightweight and powerful datetime picker.
## Changes from mix8872/yii2-flatpickr
- Dropped asset-packagist, only use npm
    To be able to use `composer install` in this standalone yii2-flatpickr, add to composer.json:
    ```
    
        "replace": {
            "bower-asset/jquery": "*",
            "bower-asset/inputmask": "*",
            "bower-asset/punycode": "*",
            "bower-asset/yii2-pjax": "*"
        }
    ```
    That tells composer, that those packages are already provided. Thus effectively preventing them from installing.

- Replaced glyphicons with fontawesome
 
## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require --prefer-dist my6uot9/yii2-flatpickr "^3.0.0"
```

or add

```
"my6uot9/yii2-flatpickr": "^3.0.0"
```

to the require section of your `composer.json` file.

## Usage

```php
<?php

use my6uot9\Flatpickr\FlatpickrWidget;

?>

<?= $form->field($model, 'published_at')->widget(FlatpickrWidget::class, [
    'locale' => strtolower(substr(Yii::$app->language, 0, 2)),
    // https://chmln.github.io/flatpickr/plugins/
    'plugins' => [
         'confirmDate' => [
               'confirmIcon'=> "<i class='fa fa-check'></i>",
               'confirmText' => 'OK',
               'showAlways' => false,
               'theme' => 'light',
         ],
    ],
    'groupBtnShow' => true,
    'options' => [
        'class' => 'form-control',
    ],
    'clientOptions' => [
        // config options https://chmln.github.io/flatpickr/options/
        'allowInput' => true,
        'defaultDate' => $model->published_at ? date(DATE_ATOM, $model->published_at) : null,
        'enableTime' => true,
        'time_24hr' => true,
    ],
]) ?>
```
