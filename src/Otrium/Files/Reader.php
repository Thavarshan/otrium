<?php

namespace Otrium\Files;

use League\Csv\Reader as CsvReader;
use Otrium\Files\Contracts\Service;

class Reader extends File implements Service
{
    /**
     * Read the given CSV file.
     *
     * @param array $records
     *
     * @return array
     */
    public function read(array $records): array
    {
        // TODO: Implement CSV file reader.
    }

    /**
     * Create the respective service provided.
     *
     * @return \Otrium\Files\Contracts\Service
     */
    public function createService(): Service
    {
        $this->service = $this->create($this->path, 'w+');

        return $this;
    }

    /**
     * Create the requested service.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function create(...$parameters)
    {
        return CsvReader::createFromPath(...$parameters);
    }
}
