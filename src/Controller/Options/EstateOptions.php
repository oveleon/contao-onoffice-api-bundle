<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller\Options;

final class EstateOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_READ, [
            'filterid',
            'filter',
            'listlimit',
            'listoffset',
            'sortby',
            'formatoutput',
            'estatelanguage',
            'outputlanguage',
            'addestatelanguage',
            'addMainLangId',
            'georangesearch',
            'addMobileUrl'
        ]);

        $this->set(Options::MODE_EDIT, [
            'estatelanguage'
        ]);
    }

    protected function getName(): string
    {
        return 'estate';
    }

    protected function getModes(): array
    {
        return [
            Options::MODE_CREATE,
            Options::MODE_READ,
            Options::MODE_EDIT
        ];
    }
}
