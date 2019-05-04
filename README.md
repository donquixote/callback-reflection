[![Build Status](https://secure.travis-ci.org/donquixote/callback-reflection.png)](https://travis-ci.org/donquixote/callback-reflection)

# callback-reflection

A library that provides a unified callback interface, with implementations wrapping different callback types.

This way, static methods, closures/lambdas, class constructor calls and other php callables become interchangeable.


## Example

```php
class C {
  private $x;
  private $y;
  public function __construct($x, $y) {
    $this->x = $x;
    $this->y = $y;
  }
}

// Callback from class constructor.
$callback = CallbackReflection_ClassConstruction::create(C::class);

// Get reflection parameters.
$parameters = $callback->getReflectionParameters();

// Invoke the callback to create a class instance.
$instance = $callback->invokeArgs(['x', 'y']);

// Generate a PHP statement.
$codegenHelper = new CodegenHelper();
$php = $callback->argsPhpGetPhp(["'x'", "'y'"], $codegenHelper);
```
