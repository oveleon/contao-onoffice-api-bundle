<?php

namespace Oveleon\ContaoOnofficeApiBundle;

class UserOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_READ, [
            'filter',
            'listlimit',
            'sortby',
            'sortorder'
        ]);
    }

    protected function setName(): void
    {
        $this->name = 'user';
    }

    protected function setModes(): void
    {
        $this->modes = [
            Options::MODE_READ
        ];
    }
}
