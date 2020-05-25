# aspnet-goutte-client


[![Build Status](https://travis-ci.org/reinaldomendes/aspnet-goutte-client.svg?branch=master)](https://travis-ci.org/reinaldomendes/aspnet-goutte-client?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/reinaldomendes/aspnet-goutte-client/badge.svg?branch=master)](https://coveralls.io/github/reinaldomendes/aspnet-goutte-client?branch=master)
## exemple call
```php
$uri = 'http://b3.php.loc/scrapper/tests/fixtures/http/responses/GET/busca-empresa-listada.html';
$formParams = [];
$files = [];
$headers = [];

$crawler = $client->request("GET", $uri, $formParams, $files, $headers);
$listCrawler = $client->eventSubmit('[value="Todas"]');
//this will submit aspnet form with __EVENTTARGET of button [value="Todas"]

```