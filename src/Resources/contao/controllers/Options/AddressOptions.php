<?php

namespace Oveleon\ContaoOnofficeApiBundle;

class AddressOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_READ, [
            'recordids',
            'filterid',
            'filter',
            'listlimit',
            'listoffset',
            'sortby',
            'sortorder',
            'formatoutput',
            'outputlanguage',
            'countryIsoCodeType',
            'addMobileUrl'
        ]);
    }

    protected function setName(): void
    {
        $this->name = 'address';
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
