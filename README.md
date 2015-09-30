# Model
A simple model.

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

A simple magic model utilizing the `__get()`, `__set()`, `__isset()`, and `__unset()` magic methods.


## Properties

A magic model has properties. Properties are defined in the static `properties` array. 

If you attempt to get, set, or unset a property that is not defined, an `OutOfBoundsException` will be thrown.

Otherwise, a model's properties can be treated like public properties of the model. 

## Data

You can hydrate a model's properties using the `hydrate()` method (keep in mind, the `hydrate()` method will ignore keys that do not match a property name):

```php

// ... continuing the example from above

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
