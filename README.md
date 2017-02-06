soap-form
=========

## requiremens
 - php7.0
 - pdo_sqlite

## Init
nezbytné kroky pro funkčnost aplikace. 

před spuštěním je nutno zkopírovat v `app/config/parameters.yml.dist` na `app/config/parameters.yml`. Soubor `parameters.yml` je v `.gitignore`

```
cp app/config/parameters.yml.dist app/config/parameters.yml
```


Pro nasazení do produkce by bylo třeba pustit příkazy s `-e prod`.

```
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

## server
spuštění dev serveru
```
cd ROOT_DIR/
php bin/console server:start
```

## test
spuštění všech testů. Testy používají vlastní DB enviromentu `test`.
```
./vendor/bin/simple-phpunit 
```

## soap description
WSDL popis SOAP služby
```
wget localhost:8000/soap?wdsl
```


