<?php

namespace Oveleon\ContaoOnofficeApiBundle;

class SearchEstateOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_READ, [
            'input',
            'extendedclaim',
            'sortby',
            'sortorder'
        ]);
    }
}
