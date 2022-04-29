<?php

namespace Oveleon\ContaoOnofficeApiBundle;

class ApiOptions extends Options
{
    protected function configure(): void
    {
        $this->set(self::MODE_READ, [
            'view',
            'addContactPerson',
        ]);
    }

    protected function setName(): void
    {
        $this->name = null;
    }

    protected function setModes(): void
    {
        $this->modes = null;
    }
}
