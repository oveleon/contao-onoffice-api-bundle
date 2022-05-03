<?php

namespace Oveleon\ContaoOnofficeApiBundle;

final class SearchAddressOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_READ, [
            'input',
            'extendedclaim',
            'includecontactdata',
            'casesensitive',
            'searchparameter',
            'listlimit',
            'sortby',
            'sortorder'
        ]);
    }
}
