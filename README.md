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

Running tests
--------------------------
Library provides a docker container for reproducible test environment.
```bash
docker run --rm --volume .:/source --workdir /source --tty r1n0x/string-language-tests-runner:1.0.0 composer run-script phpunit
```
Running tests creates an `coverege_report` folder which contains [HTML coverage report](https://docs.phpunit.de/en/11.4/code-coverage.html) - to view it simply open it in your browser.