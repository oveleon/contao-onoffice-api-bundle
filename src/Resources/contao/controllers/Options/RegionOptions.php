<?php

namespace Oveleon\ContaoOnofficeApiBundle;

class RegionOptions extends Options
{
    protected function configure(): void
    {
        $this->set(self::MODE_READ, [
            'language'
        ]);
    }
}
