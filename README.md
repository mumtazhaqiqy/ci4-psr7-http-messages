# CI4 PSR-7 HTTP Messages

This package converst codeigniter4-http object from and to objects implementing HTTP message interfaces defined by PSR-7.

## Instalation

```sh
$ composer require mumtazhaqiqy/ci4-psr7-http-messages

$ composer require nyholm/psr7
```

## Usage

The bridge provides an interface of a factory called
``CodeIgniter\Psr7Bridge\Interfaces\HttpPsr7FactoryInterface``
that builds objects implementing PSR-7 interfaces from ``IncommingRequest`` objects.

The following code snippet explains how to convert a ``CodeIgniter\HTTP\IncomingRequest``
to a ``Nyholm\Psr7\ServerRequest`` class implementing the
``Psr\Http\Message\ServerRequestInterface`` interface:

```php
<?php

use CodeIgniter\Config\Services;
use MumtazHaqiqy\Codeigniter4Psr7\HttpPsr7Factory;
use Nyholm\Psr7\Factory\Psr17Factory;

$requestCodeIgniter = Services::request();

$psr17Factory = new Psr17Factory(); 
$psrHttpFactory = new HttpPsr7Factory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
$psrRequest = $psrHttpFactory->createRequest($requestCodeIgniter);
```