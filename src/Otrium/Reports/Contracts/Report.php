<?php

namespace Otrium\Reports\Contracts;

interface Report
{
    /**
     * Generate the report.
     *
     * @param string|null $from
     *
     * @return mixed
     */
    public function generate(?string $from = null);
}
