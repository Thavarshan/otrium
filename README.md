# Otrium

## Introduction

Otrium a fast-growing e-commerce company that serves more than 2 million customers. The company has been running for several years and is now going through its growth stage. This is an internal reporting tool designed with very specific requirements and is highly opinionated.

> No frameworks of any type has been used on this application although third-party libraries were incorporated to ease development load.

***CI/CD has been set-up using Github Actions and can be found [here](https://github.com/Thavarshan/otrium/actions).***

## Before You Begin
Before you begin we recommend you read about the basic building blocks that assemble the application:
* MySQL - Go through [MYSQL Official Website](https://www.mysql.com/) and proceed to their [Official Manual](https://dev.mysql.com/doc/), which should help you understand MySQL better.
* Composer - The best way to understand PHP composer is through its [Official Website](https://getcomposer.org/), which has a [Getting Started](https://getcomposer.org/doc/00-intro.md) guide.
* PHP - PHP's [Official Website](https://www.php.net/) is a great starting point.


## Prerequisites
Make sure you have installed all of the following prerequisites on your development machine:
* Git - [Download & Install Git](https://git-scm.com/downloads). OSX and Linux machines typically have this already installed.
* PHP - [Download & InstallPHP](https://www.php.net/downloads) and the PHP package manager composer. If you encounter any problems, you can also use this [guide](https://www.php.net/manual/en/install.php) to install PHP.
* MySQL - [Download & Install MySQL](https://www.mysql.com/downloads/), and make sure it's running on the default port (3306).
* Composer - You're going to use the [Composer Package Manager](https://getcomposer.org/) to manage your PHP packages. Make sure you've installed PHP first, then install composer globally:

```bash
# MacOS
$ brew install composer
```

```bash
# Windows
$ choco install composer
```

```bash
# Linux
$ sudo apt-get update
$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
```

## Download

There are several ways you can get the Otrium App:

### Cloning The GitHub Repository

The recommended way to get Otrium App is to use git to directly clone the Otrium App repository:

```bash
$ git clone https://github.com/Thavarshan/otrium.git
```

This will clone the latest version of the Otrium App repository to a **otrium** folder.

### Downloading The Repository Zip File

Another way to use the Otrium App is to download a zip copy from the [main branch on GitHub](https://github.com/Thavarshan/otrium/archive/refs/heads/main.zip). You can also do this using the `wget` command:

```bash
$ wget https://github.com/Thavarshan/otrium/archive/refs/heads/main.zip -O otrium.zip; unzip otrium.zip; rm otrium.zip
```

Don't forget to rename **otrium-main** as **otrium**.

### Downloading The Release Build

Yet another way to download the Otrium application is to download the latest release build. You can find the latest release [here](https://github.com/Thavarshan/otrium/archive/refs/tags/v1.0.1.zip).

## Installation

Once you've downloaded the application and installed all the prerequisites, you're just a few steps away from starting to use the application.

The application comes pre-bundled with a `composer.json` file that contains the list of packages you need to start your application.

To install the dependencies, run this in the application folder from the command line:

```bash
composer update
```

This command does a few things:
* First it will install the dependencies needed for the application to run.
* If you're running in a development environment, it will then also install development dependencies needed for testing and running your application.
* To update these packages later on, just run `composer update` again.

Finally from within your command-line inside the downloaded **otrium** directory run:

```bash
cp .env.example .env && chmod +x bin/otrium
```

This is to set the application configurations and prepare them for usage.

### Configurations

A file with the name `.env` or `.env.example` has been provided with `otrium`. This is where you may save information regarding the application for instance the database credentials.

Make sure you rename `.env.example` to `.env` and set your `MySQL` database credentials in the `.env` file.

## Usage

To run the application. Open up the downloaded directory on your terminal application and run:

```bash
bin/otrium report:generate daily
```

or

```bash
bin/otrium report:generate brand
```

An argument must be provided when running the application. This is to determine what kind of report is to be generated.

> The generated report CSV files can be found within the `reports` directory.

### Available Reports

- `daily` - Generate 7 days turnover (excluding VAT) per day.
- `brand` - Generate 7 days turnover per brand (excluding VAT) per day.


## Contributing

Thank you for considering contributing to Otrium! The contribution guide can be found [here](.github/CONTRIBUTIONS).

## Code of Conduct

To ensure that the Otrium community is welcoming to all, please review and abide by the [Code of Conduct](.github/CODE_OF_CONDUCT.md).

## Security Vulnerabilities

Please review [our security policy](https://github.com/Thavarshan/otrium/security/policy) on how to report security vulnerabilities.

## Disclaimer

This is an exercise application and not a polished production-ready version. This app should not be used in a production environment and please mind the license provided.

## License

This application is open-sourced software licensed under the [MIT license](LICENSE.md).
