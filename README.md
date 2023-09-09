# Symfony Test Quest
## XDEBUG

TODO REVISAR

The default development image is shipped with [Xdebug](https://xdebug.org/),
a popular debugger and profiler for PHP.

Because it has a significant performance overhead, the step-by-step debugger is disabled by default.
It can be enabled by setting the `XDEBUG_MODE` environment variable to `debug`.

On Linux and Mac:

```
XDEBUG_MODE=debug docker compose up -d
```

On Windows (REVISAR):

```
set XDEBUG_MODE=debug&& docker compose up -d&set XDEBUG_MODE=
```

## Debugging with Xdebug and PHPStorm

First, [create a PHP debug remote server configuration](https://www.jetbrains.com/help/phpstorm/creating-a-php-debug-server-configuration.html):

1. In the `Settings/Preferences` dialog, go to `PHP | Servers`
2. Create a new server:
    * Name: `symfony` (or whatever you want to use for the variable `PHP_IDE_CONFIG`)
    * Host: `localhost` (or the one defined using the `SERVER_NAME` environment variable)
    * Port: `443`
    * Debugger: `Xdebug`
    * Check `Use path mappings`
    * Absolute path on the server: `/srv/app`

You can now use the debugger!

1. In PHPStorm, open the `Run` menu and click on `Start Listening for PHP Debug Connections`
2. Add the `XDEBUG_SESSION=PHPSTORM` query parameter to the URL of the page you want to debug, or use [other available triggers](https://xdebug.org/docs/step_debug#activate_debugger)

   Alternatively, you can use [the **Xdebug extension**](https://xdebug.org/docs/step_debug#browser-extensions) for your preferred web browser.

3. On command line, we might need to tell PHPStorm which [path mapping configuration](https://www.jetbrains.com/help/phpstorm/zero-configuration-debugging-cli.html#configure-path-mappings) should be used, set the value of the PHP_IDE_CONFIG environment variable to `serverName=symfony`, where `symfony` is the name of the debug server configured higher.

   Example:

  ```console
    XDEBUG_SESSION=1 PHP_IDE_CONFIG="serverName=Symfony" bin/console app:exam
   ```

## Troubleshooting

Inspect the installation with the following command. The Xdebug version should be displayed.

```console
docker-compose exec php php --version

PHP ...
    with Xdebug v3.x.x ...
```

# Symfony Docker

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework, with full [HTTP/2](https://symfony.com/doc/current/weblink.html), HTTP/3 and HTTPS support.

![CI](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull --wait` to start the project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Features

* Production, development and CI ready
* [Installation of extra Docker Compose services](docs/extra-services.md) with Symfony Flex
* Automatic HTTPS (in dev and in prod!)
* HTTP/2, HTTP/3 and [Preload](https://symfony.com/doc/current/web_link.html) support
* Built-in [Mercure](https://symfony.com/doc/current/mercure.html) hub
* [Vulcain](https://vulcain.rocks) support
* Native [XDebug](docs/xdebug.md) integration
* Just 2 services (PHP FPM and Caddy server)
* Super-readable configuration

**Enjoy!**

## Docs

1. [Build options](docs/build.md)
2. [Using Symfony Docker with an existing project](docs/existing-project.md)
3. [Support for extra services](docs/extra-services.md)
4. [Deploying in production](docs/production.md)
5. [Debugging with Xdebug](docs/xdebug.md)
6. [TLS Certificates](docs/tls.md)
7. [Using a Makefile](docs/makefile.md)
8. [Troubleshooting](docs/troubleshooting.md)

## License

Symfony Docker is available under the MIT License.

## Credits

Created by [Kévin Dunglas](https://dunglas.fr), co-maintained by [Maxime Helias](https://twitter.com/maxhelias) and sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
