# Number Parser

This package does one very simple thing: it converts strings containing decimal numbers into numbers (int or float). But it does this by checking if the number in the original string is valid and throwing an exception if something is wrong.

## Why would it be useful?

There are many methods in PHP to convert a string to a number. To convert an integer you can use:

```php
$i = (int) $str;
$i = intval($str);
```
For fractional you can use:

```php
$f = (float) $str;
$f = floatval($str);
```
But the problem with these methods is that they continue to work even if the string was not quite a number, or not even a number at all. For example:

```php
var_dump(intval('a')); // prints int(0)

var_dump(new stdClass()); // prints int(1) (although with a PHP Warning "Object of class stdClass could not be converted to int")
```
In error-sensitive projects, where mistakes often result in a loss of money, you may want to use the strictest possible validation of all input data. In that case, you may want to try using standard methods:

```php
$n = filter_var($str, FILTER_VALIDATE_INT);
$n = filter_var($str, FILTER_VALIDATE_FLOAT);
```

Now, if `$str` contains anything other than a number (for example, `"123a"`), then `$n` will contain `FALSE`. However, if you are building your error handling logic around exceptions, you need to add a code like this:

```php
if ($n === false) {
    throw new Exception("Invalid number in string");
}
```
Now imagine that you need to do this in multiple places! However, while `filter_var()` is good and has many options, its validators are sometimes not strict enough. For example, `filter_var()` allows numbers with spaces at the beginning or at the end (`" 123 "`), allows a minus sign before a zero (`"-0"`). Also, you may not always want to see numbers in exponential form as input (`"1.234e-4"`).

# How to use it?

It's very easy:

```
composer require gugglegum/number-parser
```

```php
use \gugglegum\NumberParser\NumberParser;

try {
    ...

    $i = NumberParser::parseInt($str1);
    $f = NumberParser::parseFloat($str2);
    $e = NumberParser::parseFloatExponent($str3);

    ...
} catch (Exception $e) {
    echo "Error: {$e->getMessage()}\n";
}
```

It makes sense to apply these methods to all numerical data obtained from the outside: `$_SERVER['argv']`, `$_GET`, `$_POST`, `$_COOKIE`, `$_ENV`, `fgets()`, etc.

### Examples of valid numbers

Integers:
```
"1"
"-12345"
"1234567890"
```
Fractional (float):
```
"0.1"
"0.10"
".1"
"1.23"
```
Fractional with exponent:
```
"1e2"
"1e+2""
"1e-2"
"1.23e2"
"12.3e1"
"0.1e2"
".123e-2"
```

### Examples of invalid numbers

Integers:
```
"a567"
"567a"
" 567"
"567 "
"56_7"
"-0"
"00"
"-00"
"01"
"+1"
".1"
"1e2"
""
```
Fractional (float):
```
"a56.7"
"56.7a"
" 56.7"
"56.7 "
"5_6.7"
"00"
"00.0"
"0"
"-0.0"
"-.0"
"+1.2"
"."
""
```
Fractional with exponent:
```
"e",
"1e",
"1e+",
"1e-",
"e+2",
"e-2",
".e2",
```

## How do I run the tests?

```
composer intall
vendor/bin/phpunit
```
