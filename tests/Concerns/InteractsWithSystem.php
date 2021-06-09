<?php

namespace Otrium\Tests\Concerns;

trait InteractsWithSystem
{
    /**
     * Determine whether the current environment is Windows based.
     *
     * @return bool
     */
    protected function isWindows(): bool
    {
        return \PHP_OS_FAMILY === 'Windows';
    }
}
