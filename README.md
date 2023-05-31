# CSRF token handling for vanilla php systems

This package will make it easy for you to handle CSRF tokens in forms, for systems
that might not use fancy frameworks etc.

The idea itself comes from here: https://brightsec.com/blog/csrf-token/


# Getting started

The package is availabe here on Github and on Packagist

* https://github.com/jimmiw/csrf
* https://packagist.org/packages/jimmiw/csrf

## Installing

To use the system, simply require it using composer:

```
composer require jimmiw/csrf
```

## Using the component

Using the component is pretty easy, simply construct the class and call generateToken.

```
use Westsworld\CSRF\Generator;

// you can add a custom session handler, when creating the token handler in the construct method.
$tokenHandler = new Generator();
// the generated token is stored in the session
$token = $tokenHandler->generateToken();

<form method="post">
  <input type="hidden" name="<?php echo $token->getKey(); ?>" value="<?php echo $token->getValue(); ?>" />
  <input type="hidden" name="token-key" value="<?php echo $token->getKey(); ?>" />
  ... other form fields here
</form>
```

When the form is posted to your page, simply create a new token handler and call validateToken:

```
$tokenHandler = new Generator();
if (! $tokenHandler->validateToken($_POST['token-key'])) {
    exit('token is not valid!');
} else {
    // handle the form saving here
}
```
