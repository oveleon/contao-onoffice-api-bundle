<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller\Options;

final class SearchSearchCriteriaOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_READ, [
            'input',
            'extendedclaim',
            'searchdata',
            'outputall',
            'outputfields',
            'groupbyaddress',
            'offset',
            'limit',
            'order'
        ]);
    }
}
