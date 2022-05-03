<?php

namespace Oveleon\ContaoOnofficeApiBundle;

final class QualifiedSuitorOptions extends Options
{
    protected function configure(): void
    {
        $this->set(self::MODE_READ, [
            'estatedata'
        ]);
    }
}
