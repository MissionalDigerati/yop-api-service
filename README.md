# Year of Prayer: API Service

This package provides a common PHP service for engaging your instance of the Year of Prayer API.  It handles the communication between your application and the API.

## Usage

Here is how to set up the API service:

1. Install the library

```
composer require https://github.com/MissionalDigerati/yop_api_service:master
```
2. Add your code
```
<?php
use YearOfPrayer\ApiService\ApiService;
use YearOfPrayer\ApiService\ConsumerService;
use YearOfPrayer\ApiService\HttpService;
use YearOfPrayer\ApiService\PrayerService;

$apiService = new ApiService(new HttpService('YOUR API URL'), new ConsumerService(), new PrayerService());
```

## Development

This repository is following the branching technique described in [this blog post](http://nvie.com/posts/a-successful-git-branching-model/), and the semantic version set out on the [semantic versioning website](http://semver.org/).  Here are some of our language specific standards:

**PHP**

* Coding Standard: [PHP-FIG PSR12](https://www.php-fig.org/psr/psr-12/)
* Directory Structure: [Outline in This Article](https://blog.nikolaposa.in.rs/2017/01/16/on-structuring-php-projects/)
* Test Driven Development using PHPUnit.

## License

This code is released under the [GNU General Public License](https://www.gnu.org/licenses/gpl-3.0.en.html).
