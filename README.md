# asp.net-goutte-client


[![Build Status](https://travis-ci.org/reinaldomendes/asp.net-goutte-client.svg?branch=master)](https://travis-ci.org/reinaldomendes/asp.net-goutte-client?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/reinaldomendes/asp.net-goutte-client/badge.svg?branch=master)](https://coveralls.io/github/reinaldomendes/asp.net-goutte-client?branch=master)
## exemple call
```php
$uri = 'http://b3.php.loc/scrapper/tests/fixtures/http/responses/GET/busca-empresa-listada.html';

$crawler = $client->request("GET", $uri, $formParams, $files, $headers);
$listCrawler = $client->eventSubmit('[value="Todas"]');//this will submit aspnet form with __EVENTTARGET of button [value="Todas"]

```