# Model
A simple magic model.

```php
use Jstewmc\Model;

// define a concrete model with a single property, "foo"
class Foo extends Model
{
    protected static $properties = ['foo'];
}

// instantiate the new model
$model = new Foo();

// set foo's value
$model->foo = 'bar';

// test foo's value
isset($model->foo);  // returns true
echo $model->foo;    // returns "bar"
unset($model->foo);  // returns void
echo $model->foo;    // returns null
isset($model->foo);  // returns false
```

## Properties

A magic model has properties. Properties are defined in the static `properties` array. 

You can access a model's properties like public properties thanks to the magic `__get()`, `__set()`, `__isset()`, and `__unset()` methods. If you attempt to get, set, or unset a property that is not defined, an `OutOfBoundsException` will be thrown.

## Data

You can hydrate a model's properties using the `hydrate()` method (keep in mind, the `hydrate()` method will ignore keys that do not match a property name):

```php

// ...continuing the example from above

$data = ['foo' => 'qux', 'baz' => 'bar'];

$model->hydrate($data);

echo $model->foo;    // returns "qux"
isset($model->baz);  // returns false
```

## Version

### dev-master


## Author

[Jack Clayton](mailto:clayjs0@gmail.com)


## License

[MIT](https://github.com/jstewmc/model/blob/master/LICENSE)
