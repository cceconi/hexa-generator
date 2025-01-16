# Hexa-generator

Binary to generate hexagonal architecture compliant classes for hexa-lib

## List of availables commands

Generate all classes for one use case and optionally roles

```shell
$ bin/hg hexa:generate:use-case

$ bin/hg hexa:generate:use-case MyBusinessTheme TestMyCommand "src\Domain" "MyApp\Domain" 
```

Generate a class role and optionally anothers roles

```shell
$ bin/hg hexa:generate:role

$ bin/hg hexa:generate:role Admin "src\Domain" "MyApp\Domain" 
```

## Todo

* Other classes (Filters, Presenters, etc.)
* Tests ?