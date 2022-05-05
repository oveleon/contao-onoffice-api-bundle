<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller\Options;

final class RegionOptions extends Options
{
    protected function configure(): void
    {
        $this->set(self::MODE_READ, [
            'language'
        ]);
    }
}
