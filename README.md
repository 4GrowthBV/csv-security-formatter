# csv-security-formatter

Formatter for [league/csv](http://csv.thephpleague.com/) package to increase security for csv exports with user 
generated content. For more information about the security risks of user generated content in csv exports, please read 
[http://georgemauer.net/2017/10/07/csv-injection.html](http://georgemauer.net/2017/10/07/csv-injection.html). 

## Installation

You can install the package via composer:

`composer require inthere/csv-security-formatter`

## Usage

Start the formatter. The formatter accept a boolean as parameter, provide `false` when you want to remove the formula 
instead of escaping. 
```php
$csvSecurityFormatter = new \InThere\CsvSecurityFormatter\CsvSecurityFormatter();
```

Provide the formula to the writer.
```php
$writer = Writer::createFromFileObject(new SplTempFileObject());
$writer->addFormatter($csvSecurityFormatter);
$writer->insertOne(['=2*5', 'foo', 'bar']);
```

Create the csv.
```php
$writer->__toString();
```

## Tests

`$ vendor/bin/phpunit`

## Contributors

Contributions are welcome. We accept contributions via pull requests on Github.

## License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.

## About InThere

InThere - "The training Through Gaming Company" - speeds up training your team and change processes by providing a 
micro-training concept based on serious games.  
