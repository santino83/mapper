# Mapper

An Object-2-Object PHP Mapper to map either an object or native type into another object or native type.

# Installation

```sh
$ git clone https://github.com/santino83/mapper.git
```

# How to use

Full-doc is going to coming. See [tests](https://github.com/santino83/mapper/tree/master/tests) for more example. 

Basi usage (only native types):

```php
use GSDCode\Mapper\ObjectMapperBuilder;

include 'vendor/autoload.php';

$config = [
    'settings'=>['date_format'=>'Y-m-d'],
    'converters'=>[],
    'mappings'=>[]
    ];

$mapperBuilder = new ObjectMapperBuilder();

$mapper = $mapperBuilder->build($config);
/*@var $mapper GSDCode\Mapper\Mapper */


//string to int
$int = $mapper->map("100", "int");

echo 'INT: '. ( $int === 100 ? 'TRUE' : 'FALSE')."\n";

//int to string
$str = $mapper->map(100, "string");

echo 'STRING: '.("100" === $str ? 'TRUE' : 'FALSE')."\n";

//string to bool
$bool = $mapper->map("TRUE","bool");

echo 'BOOL: '.(TRUE === $bool ? 'TRUE' : 'FALSE')."\n";

//bool to string
$bstr = $mapper->map(true, "string");

echo 'BOOL_STRING: '.("TRUE" === $bstr ? 'TRUE' : 'FALSE')."\n";

//int to bool
$bint = $mapper->map(1,"bool");

echo 'BOOL_INT: '.(TRUE === $bint ? 'TRUE' : 'FALSE')."\n";

//bool to int
$ibool = $mapper->map(true,'int');

echo 'INT_BOOL: '.(1 === $ibool ? 'TRUE' : 'FALSE')."\n";

//date to string
$date = new \DateTime('2016-01-01 15:30:45');

$dstr = $mapper->map($date,"string");

echo 'DATE_STRING: '.("2016-01-01" === $dstr ? 'TRUE' : 'FALSE')."\n";

//date to int
$dint = $mapper->map($date, "int");

echo 'DATE_INT: '.($date->getTimestamp() === $dint ? 'TRUE' : 'FALSE')."\n";
```

Basic usage (custom classes mapping):

```php

use GSDCode\Mapper\ObjectMapperBuilder;

include 'vendor/autoload.php';

class ClassA{
    
    /**
     *
     * @var string
     */
    public $prop;

}

class ClassB{
    
    /**
     *
     * @var \DateTime
     */
    public $prop2;
}



$config = [
    'settings'=>['date_format'=>'Y-m-d'],
    'converters'=>[],
    'mappings'=>[
        ['classA' => ClassA::class, 'classB' => ClassB::class, 'fields'=>[ ['fieldA'=>'prop','fieldB'=>'prop2', 'converter'=>null] ]]
     ]
    ];

$mapperBuilder = new ObjectMapperBuilder();

$mapper = $mapperBuilder->build($config);
/*@var $mapper GSDCode\Mapper\Mapper */

//ClassA to ClassB
$a = new ClassA();
$a->prop = '2016-01-01';

$b = $mapper->map($a, ClassB::class);

echo "ClassB::prop2 is Date? ".($b->prop2 instanceof \DateTime ? 'TRUE' : 'FALSE')."\n";
echo "Date is 2016-01-01? ".($b->prop2->format('Y-m-d') === '2016-01-01' ? 'TRUE' : 'FALSE')."\n";


//ClassB to ClassA
$b = new ClassB();
$b->prop2 = new \DateTime("2016-01-01 15:03:45");

$a = $mapper->map($b, ClassA::class);
echo "ClassA::prop is 2016-01-01 ? ".($a->prop === "2016-01-01" ? 'TRUE' : 'FALSE')."\n";
```

Basic usage (custom classes with same field names):

```php
use GSDCode\Mapper\ObjectMapperBuilder;

include 'vendor/autoload.php';

class ClassA{
    
    /**
     *
     * @var string
     */
    public $prop;

}

class ClassB{
    
    /**
     *
     * @var \DateTime
     */
    public $prop;
}



$config = [
    'settings'=>['date_format'=>'Y-m-d'],
    'converters'=>[],
    'mappings'=>[
        ['classA' => ClassA::class, 'classB' => ClassB::class, 'fields'=>[]]
     ]
    ];

$mapperBuilder = new ObjectMapperBuilder();

$mapper = $mapperBuilder->build($config);
/*@var $mapper GSDCode\Mapper\Mapper */

//ClassA to ClassB
$a = new ClassA();
$a->prop = '2016-01-01';

$b = $mapper->map($a, ClassB::class);

echo "ClassB::prop is Date? ".($b->prop instanceof \DateTime ? 'TRUE' : 'FALSE')."\n";
echo "Date is 2016-01-01? ".($b->prop->format('Y-m-d') === '2016-01-01' ? 'TRUE' : 'FALSE')."\n";


//ClassB to ClassA
$b = new ClassB();
$b->prop = new \DateTime("2016-01-01 15:03:45");

$a = $mapper->map($b, ClassA::class);
echo "ClassA::prop is 2016-01-01 ? ".($a->prop === "2016-01-01" ? 'TRUE' : 'FALSE')."\n";
```

# Configuration

Mapping configuration could be loaded from a YAML file:

```yml
settings:
    date_format: Y-m-d H:i:s
    
converters:
    - my_first_converter
    - my_second_converter

mappings:
    - classA: Tests\Data\Bean\ChildBean1       
      classB: Tests\Data\Target\TargetChild1
      
    - classA: Tests\Data\Bean\ChildBean2
      classB: Tests\Data\Target\TargetChild2
      fields:
          - fieldA: childProp1
            fieldB: prop4
          - fieldA: childProp2
            fieldB: prop5
          - fieldA: childProp3
            fieldB: prop6
```

To load the configuration:

```php
use GSDCode\Mapper\Config\YamlLoader;
use Symfony\Component\Config\FileLocator;

include 'vendor/autoload.php';

$resource = 'config.yml';
$loader = new YamlLoader(new FileLocator(__DIR__));
$config = $loader->load($resource);

print_r($config);

```

See [tests](https://github.com/santino83/mapper/tree/master/tests) for more example. 

# Note

It's better to have PHPDoc on both source and target properties, otherwise if source properties are not null, mapper is able to guess the type as right as possible. Converting from a source property to a target property that has no PHPDoc is not possibile.

# Ready to contributing

```ssh
$ git clone https://github.com/santino83/mapper.git
$ cd mapper
$ composer install
$ cp phpunit.xml.dist phpunit.xml
```

# Testing

Library use phpunit for testing:

```ssh
$ cd mapper
$ phpunit -c .
```
