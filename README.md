# Cookie Library

The `drewlabs/cookie` library provide a cookie an Object Oriented component for building and parsing cookies. The library also provides various immutable methods on the `Cookie` instance to update cookie instance once created.

## Usage

The library provides developers with a factory class for creating cookie instances. Using `cookie factory` class to create cookies follow the syntaxes below:

```php
<?php

use Drewlabs\Cookie\Factory;

// Creates a session cookie with name and value as first parameters
$cookie = Factory::new()->create('sessionId',uniqid('session').time(), 0);
```

Using the `factory` class developers can create cookie instance from raw cookie string:

```php
use Drewlabs\Cookie\Factory;

$expiresAt = (new \DateTimeImmutable())->format('U');
$cookie = Factory::new()->createFromString("sessionId=e8bb43229de9; Expires=$expiresAt; Domain=foo.example.com; Path=/; Secure; HttpOnly");
```


**Note** using the factory class is the recommended way to create cookie instance, but you can use `Cookie` class constructor to create `cookie` instances. The constructor takes the same parameters as the `Factory::create()` method. Consult the documentation reference for more information about supported parameters.

### Basic Cookie API

After `cookie` instances are created, you can modify their value by creating modified instance of the cookie. To prevent OOP issue related to internal state changes, the `cookie` API does not directly modify their internal state... instead, it provide method for creating modified copy of the the source instance. Here is a list of methods for updating `cookie` instances values:

- `withValue`

  ```php
  <?php
  $cookie = Factory::new()->create('id', "oldCookieValue");

  $cookie2 = $cookie->withValue("newCookieValue");

  echo $cookie->getValue(); // oldCookieValue
  echo $cookie2->getValue(); //newCookieValue
  ```
- `withDomain`

  The `withDomain` method allow you update the domain for which cookie was generated.

  ```php
  <?php
  $cookie = Factory::new()->create('id', uniqid('cookie').time());
  $cookie2 = $cookie->withDomain("com.azlabs.xyz");

  echo $cookie->getDomain(); // null
  echo $cookie2->getDomain(); //com.azlabs.xyz
  ```
- `withPath`

  The `withPath` method updates the url path on which cookie might be applied.

  ```php
  <?php
  $cookie = Factory::new()->create('id', uniqid('cookie').time());
  $cookie2 = $cookie->withPath("/auth");

  echo $cookie->getPath(); // /
  echo $cookie2->getPath(); // /auth
  ```
- `withHttpOnly
  `
  `withHttpOnly` update the cookie to be only send through http header and not be modified by any frontend code. It tell browsers to enforce cookie rules and protect cookie againt frontend attacks.

  ```php
  $cookie = Factory::new()->create('id', uniqid('cookie').time());
  $cookie2 = $cookie->withHttpOnly();
  ```
- `withSecure
  `
  `withSecure` enforce cookie rules, by forcing browsers to send cookie though a secure connection.

  ```php
  <?php
  $cookie = Factory::new()->create('id', uniqid('cookie').time());
  $cookie2 = $cookie->withSecure();
  ```
- `__toString() `
  `cookie` insances API also provides a `__toString()` method that allows you to convert `cookie` object to it string representation that can be used as value to `Set-Cookie` header.
