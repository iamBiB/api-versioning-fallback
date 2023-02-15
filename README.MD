# Lumen Api Versioning

A package that allows you to have a fallback version for your Lumen api

## Description
This package allows you to have fallback versions for your api. Basically if you have v1 in place and you want to update your app to v2 but you don't have time to update all methods, this pack allows you to take a break.
Eg. #1 **api.dev/v2/users** will fallback to **api.dev/v1/users** if v2 is not found
Eg. #2 **api.dev/v3/users** will fallback to **api.dev/v2/users** if found, if not **api.dev/v1/users**

## Installation
```shell
    composer require iambib/lumen-api-versioning
```
`config file`
Create a config file named api-versioning.php and add the following lines
```php
return [
    /**
     * Enable the fallback
     */
    'enable' => true,
    /**
     * Available api versions
     */

    'available_versions' => [
        'v1',
        'v2',
    ],
    /**
     * Set them in order you want the fallback to happen
     * Eg. If v4 is not found v3 is the first one to check if exists. If not, v2 then v1.
     */
    'api_fallbacks' => [
        'v3', 'v2', 'v1',
    ],
];
```
`bootstrap/app.php`

```php
	$app = new \iAmBiB\ApiVersionFallback\Extension\Application(
		$_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
	);
 	...
   $app->middleware([
		...
		'api_versioning_fallback' => \iAmBiB\ApiVersionFallback\Middleware\ApiVersioningFallback::class,
	]);
	...
	$app->configure('api-versioning');
```