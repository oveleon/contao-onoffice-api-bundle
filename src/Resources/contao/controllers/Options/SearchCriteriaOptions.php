<?php

namespace Oveleon\ContaoOnofficeApiBundle;

class SearchCriteriaOptions extends Options
{
    protected function configure(): void
    {
        $this->set(self::MODE_READ, [
            'mode',
            'ids'
        ]);
    }
}
