Version 2.0.0 2018-03-09
- Drop compatibility with PHP 5.6, minimum is PHP 7.0
- Use typehints and strict mode
- Data classes are now immutable (value objects)
- Introduce collections of cities and locations
- Use traits for common properties
- Add tests for PdoImporter using an extract of the raw data
- Do not remove the raw table, just delete all contents

Version 1.1.1 2018-02-02
- Make use of phpstan, include in travis and in CONTRIBUTING.md
- Fix docblocks according to phpstan and scrutinizer
- Add gitter to README.md
- Check compatibility with PHP 7.2, remove HHVM
- update composer.json, .travis.yml, .scrutinizer.yml

Version 1.1.0 2017-01-26
- Rename interface `DataGateway` to `DataGatewayInterface`
- Travis integration and php 7.1
- Code comments cleanup

Version 1.0.1 2017-01-26
- Document changes to data source license "uso libre mx"
- Move assets to `/assets/` instead of `/test/assets/`
- Apply code style
- Add badges to README
- Add more files to make it a first class library

Version 1.0.0
- First release
