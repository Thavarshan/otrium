<?php

namespace Otrium\Reports\Contracts;

interface Report
{
    /**
     * Generate the report.
     *
     * @param mixed $parameters
     *
     * @return mixed
     */
    public function generate(...$parameters);
}
