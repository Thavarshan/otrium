<?php

namespace Otrium\Reports\Contracts;

interface Report
{
    /**
     * Generate the report.
     *
     * @return mixed
     */
    public function generate();
}
