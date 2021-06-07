# Otrium

## Introduction

Otrium a fast growing e-commerce company which serves more than 2 million customers. The company has been running for several years and is now going through its growth stage. This is an internal reporting tool designed with very specific requirements and in an opinionated manner.

## Installation

Download the latest release of the application [here](./).

Otrium uses `MySQL` as it's primary database management system. Fake database records have been provided for testing purposes and can be found within the `data` directory.

After you set up your database and load it with the fake data. Open up the downloaded directory from you terminal application and run:

```shell
cp .env.example .env && chmod +x bin/otrium
```

This is to set the application configurations and prepare it for usage.

#### Configurations

A file with the name `.env` or `.env.example` has been provided with `otrium`. This is where you may save information regarding the application for instance the database credentials.

## Usage

To run the application. Open up the downloaded directory on your terminal application and run:

```shell
bin/otrium daily
```

or

```shell
bin/otrium brand
```

An argument must be provided when running the application. This is to determine what kind of report is to be generated.

> The generated report CSV files can be found within the `reports` directory.

##### Available Reports

- `daily` - Generate 7 days turnover (excluding VAT) per day.
- `brand` - Generate 7 days turnover per brand (excluding VAT) per day.


## Contributing

Thank you for considering contributing to Otrium! The contribution guide can be found [here](.github/CONTRIBUTIONS).

## Code of Conduct

In order to ensure that the Otrium community is welcoming to all, please review and abide by the [Code of Conduct](.github/CODE_OF_CONDUCT.md).

## Security Vulnerabilities

Please review [our security policy](https://github.com/Thavarshan/otrium/security/policy) on how to report security vulnerabilities.

## License

This application is open-sourced software licensed under the [MIT license](LICENSE.md).
