<?php

namespace Oveleon\ContaoOnofficeApiBundle;

class EstateOptions extends Options
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
    }

    protected function setName(): void
    {
        $this->name = 'estate';
    }

    protected function setModes(): void
    {
        $this->modes = [
            Options::MODE_CREATE,
            Options::MODE_READ,
            Options::MODE_EDIT
        ];
    }
}
