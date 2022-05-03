<?php

namespace Oveleon\ContaoOnofficeApiBundle;

class QualifiedSuitorOptions extends Options
{
    protected function configure(): void
    {
        $this->set(self::MODE_READ, [
            'estatedata'
        ]);
    }
}
