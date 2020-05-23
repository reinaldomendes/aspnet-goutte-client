# asp.net-goutte-client

## exemple call
```php
$uri = 'http://b3.php.loc/scrapper/tests/fixtures/http/responses/GET/busca-empresa-listada.html';

$crawler = $client->request("GET", $uri, $formParams, $files, $headers);
$listCrawler = $client->eventSubmit('[value="Todas"]');//this will submit aspnet form with __EVENTTARGET of button [value="Todas"]

```