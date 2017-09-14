# Omnipay: Mpay

**Mpay driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/lokielse/omnipay-mpay.png?branch=master)](https://travis-ci.org/wjminions/omnipay-mpay)
[![Latest Stable Version](https://poser.pugx.org/lokielse/omnipay-mpay/version.png)](https://packagist.org/packages/wjminions/omnipay-mpay)
[![Total Downloads](https://poser.pugx.org/lokielse/omnipay-mpay/d/total.png)](https://packagist.org/packages/wjminions/omnipay-mpay)

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements UnionPay support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "wjminions/omnipay-mpay": "dev-master"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package: