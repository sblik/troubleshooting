## Introduction

We have an intermittent error that occurs on the e-signature plugin after signing. This error causes a the PHP script execution to fail due to a fatal error.

```
PHP Fatal error:  Declaration of Mpdf\PsrHttpMessageShim\Request::withRequestTarget(string $requestTarget): Psr\Http\Message\RequestInterface must be compatible with Psr\Http\Message\RequestInterface::withRequestTarget($requestTarget) in /home/customer/www/cryo.smplfy.dev/public_html/wp-content/plugins/e-signature/add-ons/esig-save-as-pdf/vendor/mpdf/psr-http-message-shim/src/Request.php on line 88
```
or
```
PHP Fatal error:  Declaration of Mpdf\PsrHttpMessageShim\Request::withMethod(string $method): Psr\Http\Message\RequestInterface must be compatible with Psr\Http\Message\RequestInterface::withMethod($method) in /home/customer/www/cryo.smplfy.dev/public_html/wp-content/plugins/e-signature/add-ons/esig-save-as-pdf/vendor/mpdf/psr-http-message-shim/src/Request.php on line 105
```

## Plugins Installed

The following plugins 

- WP E-Signature v 1.8.7
- WP E-Signature Business add-ons v 1.8.7
- Signature Add-On for Gravity Forms by ApproveMe.com 1.8.3

- Gravity PDF v 6.7.4

- Post SMTP v 2.8.11


## Issue Explained

The `Request` class defined at `e-signature/add-ons/esig-save-as-pdf/vendor/mpdf/psr-http-message-shim/src/Request.php` has a method signature without a `string` parameter type.

```
public function withRequestTarget($requestTarget): RequestInterface
```

This Request.php class implements `\Psr\Http\Message\RequestInterface` which is defined twice

1. In the e-signature plugin
```
\e-signature\add-ons\esig-save-as-pdf\vendor\psr\http-message\src\RequestInterface.php

 public function withRequestTarget(string $requestTarget): RequestInterface;
```
2. In the e-signature-business-add-ons plugin
```
e-signature-business-add-ons\esig-dropbox-sync\dropbox\vendor\psr\http-message\src\RequestInterface.php

public function withRequestTarget($requestTarget);
```

Depending on the order that the plugins load in wordpress we can get a miss match between the Request.php implementation vs interface
