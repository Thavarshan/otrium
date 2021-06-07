<?php

namespace Otrium\Files\Contracts;

interface Service
{
    /**
     * Create the requested service.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function create(...$parameters);
}
