### Introduction

Our wordpress installation occasionally encounters a `PHP Fatal error` because of a signature mismatch between different version of the `psr\http-message` package that are in various plugins that we have installed.

The following packages have version 7.2+ of `psr\http-message` which has type support and primitive types are specified in many of the function parameters.

1. plugins\e-signature\add-ons\esig-save-as-pdf\vendor\psr\http-message\src
2. plugins\gravity-forms-pdf-extended\vendor\psr\http-message\src
3. plugins\gravity-forms-pdf-extended\vendor_prefixed\psr\http-message\src

Whereas there are packages that use an older version that is 9 years old (2015).
Ref: - https://github.com/php-fig/http-message/releases, https://www.php-fig.org/psr/psr-7/meta/#72-type-additions

1. plugins\post-smtp\Postman\Postman-Mail\libs\vendor_prefixed\psr\http-message\src
2. plugins\e-signature-business-add-ons\esig-dropbox-sync\dropbox\vendor\psr\http-message\src

When either of the `plugin post-smtp` or `e-signature-business-add-ons` plugins load last the old version of the `http-message` package is loaded and when the php script executes we have a fatal error due to a miss match between the implementation and the interface.


### Effected Files and Functions
```
RequestInterface.php
	withRequestTarget(string $requestTarget): RequestInterface;
	withMethod(string $method): RequestInterface;
	withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface;
MessageInterface.php
	withProtocolVersion(string $version);
	hasHeader(string $name);
	getHeader(string $name);
	getHeaderLine(string $name);
	withHeader(string $name, $value);
	withAddedHeader(string $name, $value);
	withoutHeader(string $name);
ResponseInterface.php
    withStatus(int $code, string $reasonPhrase = '');
ServerRequestInterface.php
	withAttribute(string $name, $value): ServerRequestInterface;
	withoutAttribute(string $name): ServerRequestInterface;
StreamInterface.php
	seek(int $offset, int $whence = SEEK_SET): void;
	write(string $string): int;
	read(int $length): string;
	getMetadata(?string $key = null);
UploadedFileInterface.php
	moveTo(string $targetPath): void;
UriInterface.php
	withScheme(string $scheme): UriInterface;
	withUserInfo(string $user, ?string $password = null): UriInterface;
	withHost(string $host): UriInterface;
	withPort(?int $port): UriInterface;
	withPath(string $path): UriInterface;
	withQuery(string $query): UriInterface;
	withFragment(string $fragment): UriInterface;
```