<?php

namespace Otrium\Files;

use Otrium\Files\Contracts\Service;

abstract class File
{
    /**
     * The full path to the file being handled.
     *
     * @var string
     */
    protected $path;

    /**
     * The prefix used for name of each file generated.
     *
     * @var string
     */
    protected $prefix = 'otrium_csv';

    /**
     * Create a new file handler instance.
     *
     * @param string $path
     *
     * @return void
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Create the respective service provided.
     *
     * @return \Otrium\Files\Contracts\Service
     */
    abstract public function createService(): Service;

    /**
     * The name of the file being handled.
     *
     * @return string
     */
    public function fileName(): string
    {
        $file = "{$this->prefix}_" . date('Y-m-d-hia') . '.csv';

        return "{$this->path}/{$file}";
    }

    /**
     * Set the file prefix.
     *
     * @param string $prefix
     *
     * @return void
     */
    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }
}
