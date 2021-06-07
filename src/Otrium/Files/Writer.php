<?php

namespace Otrium\Files;

use RuntimeException;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer as CsvWriter;
use Otrium\Files\Contracts\Service;

class Writer extends File implements Service
{
    /**
     * Write given data records to a CSV file.
     *
     * @param string $name
     * @param array  $records
     *
     * @return void
     */
    public function write(string $name, array $records): void
    {
        $this->setPrefix($name);

        try {
            $this->createService();

            $this->service->insertAll($records);
        } catch (CannotInsertRecord $e) {
            throw new RuntimeException('Failed to write to report file becuase ' . $e->getMessage());
        }
    }

    /**
     * Create the respective service provided.
     *
     * @return \Otrium\Files\Contracts\Service
     */
    public function createService(): Service
    {
        $file = $this->fileName();

        if (! \file_exists($file)) {
            @touch($file);
        }

        $this->service = $this->create($file, 'w+');

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
        return CsvWriter::createFromPath(...$parameters);
    }
}
