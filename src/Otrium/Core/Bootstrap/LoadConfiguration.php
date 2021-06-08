<?php

namespace Otrium\Core\Bootstrap;

use Exception;
use SplFileInfo;
use Illuminate\Config\Repository;
use Symfony\Component\Finder\Finder;
use Otrium\Core\Contracts\Application;
use Otrium\Core\Contracts\Bootstrap as BootstrapperInterface;
use Illuminate\Contracts\Config\Repository as RepositoryContract;

class LoadConfiguration implements BootstrapperInterface
{
    /**
     * Bootstrap the given application.
     *
     * @param \Otrium\Core\Contracts\Application $app
     *
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        $items = [];

        $app->instance('config', $config = new Repository($items));

        $this->loadConfigurationFiles($app, $config);

        date_default_timezone_set($config->get('app.timezone', 'UTC'));

        mb_internal_encoding('UTF-8');
    }

    /**
     * Load the configuration items from all of the files.
     *
     * @param \Otrium\Core\Contracts\Application      $app
     * @param \Illuminate\Contracts\Config\Repository $repository
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function loadConfigurationFiles(Application $app, RepositoryContract $repository): void
    {
        $files = $this->getConfigurationFiles($app);

        if (! isset($files['app'])) {
            throw new Exception('Unable to load the "app" configuration file.');
        }

        foreach ($files as $key => $path) {
            $repository->set($key, require $path);
        }
    }

    /**
     * Get all of the configuration files for the application.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return array
     */
    protected function getConfigurationFiles(Application $app)
    {
        $files = [];

        $configPath = realpath($app->configPath());

        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $directory = $this->getNestedDirectory($file, $configPath);

            $files[$directory . basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        ksort($files, \SORT_NATURAL);

        return $files;
    }

    /**
     * Get the configuration file nesting path.
     *
     * @param \SplFileInfo $file
     * @param string       $configPath
     *
     * @return string
     */
    protected function getNestedDirectory(SplFileInfo $file, string $configPath): string
    {
        $directory = $file->getPath();

        if ($nested = trim(str_replace($configPath, '', $directory), \DIRECTORY_SEPARATOR)) {
            $nested = str_replace(\DIRECTORY_SEPARATOR, '.', $nested) . '.';
        }

        return $nested;
    }
}
