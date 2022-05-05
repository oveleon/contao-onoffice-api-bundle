<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller\Options;

final class QualifiedSuitorOptions extends Options
{
    protected function configure(): void
    {
        $this->set(self::MODE_READ, [
            'estatedata'
        ]);
    }
}
