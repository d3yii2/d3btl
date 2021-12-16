#d3btl"

## Features

Reads BTL files created by design2machine interface

https://design2machine.com/btl/btl_v106.pdf 



## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ composer require d3yii2/d3btl "*"
```

or add

```
"d3yii2/d3btl": "*"
```

to the `require` section of your `composer.json` file.


add to migration path

```php

'class' => 'yii\console\controllers\MigrateController',
'migrationPath' => [
                    '@d3yii2/d3btl/migrations',
]
```


## Usage

command add, reads files and saves their data in database

```bash

php yii btl/add path/to/file

```
