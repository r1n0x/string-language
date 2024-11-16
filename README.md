[![codecov](https://codecov.io/github/r1n0x/string-language/graph/badge.svg?token=VAFFHQTTUW)](https://codecov.io/github/r1n0x/string-language)

Status
--------------------------
Project is currently being worked on, everything is looking good tho.

About
--------------------------
StringLanguage library targets people who want to are in need of simple string
concatenation language. The main focus while developing was to be extendable easily.

Documentation
--------------------------
For documentation visit [documentation page](/TODO).

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