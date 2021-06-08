<?php

namespace Otrium\Core\Contracts;

use Illuminate\Contracts\Container\Container;

interface Application extends Container
{
    /**
     * Get the base path of the Otrium application installation.
     *
     * @param string $path
     *
     * @return string
     */
    public function basePath(string $path = ''): string;

    /**
     * Get the path to the application configuration files.
     *
     * @param string $path
     *
     * @return string
     */
    public function configPath(string $path = ''): string;

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version(): string;

    /**
     * Determine if the application has booted.
     *
     * @return bool
     */
    public function isBooted(): bool;

    /**
     * Get the environment file the application is using.
     *
     * @return string
     */
    public function environmentFile(): string;

    /**
     * Get the application configurations.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function config(?string $key = null, $default = null);
}
