[![codecov](https://codecov.io/github/r1n0x/string-language/graph/badge.svg?token=VAFFHQTTUW)](https://codecov.io/github/r1n0x/string-language)

About
--------------------------
This library provides a simple string language which in the end returns only a string, thus why "String Language" name - it exists purely because concatenating strings with other similar libraries seemed overkill and not easy to read.

Documentation
--------------------------
For documentation visit [documentation page](https://r1n0x.github.io/string-languag).

Running tests (PHPUnit)
--------------------------
Library provides a docker container for reproducible test environment.
```bash
docker run --rm --volume .:/source --workdir /source --tty r1n0x/string-language-container:1.0.1 composer run-script phpunit
```
Running tests creates an `coverege_report` folder which contains [HTML coverage report](https://docs.phpunit.de/en/11.4/code-coverage.html) - to view it simply open it in your browser.

Running types validator (PHPStan)
--------------------------
Library provides a docker container for reproducible PHPStan environment.
```bash
docker run --rm --volume .:/source --workdir /source --tty r1n0x/string-language-container:1.0.1 composer run-script phpstan
```

Running code style validator (CS-Fixer)
--------------------------
Library provides a docker container for reproducible CS-Fixer environment.
```bash
docker run --rm --volume .:/source --workdir /source --tty r1n0x/string-language-container:1.0.1 composer run-script csfixer
```