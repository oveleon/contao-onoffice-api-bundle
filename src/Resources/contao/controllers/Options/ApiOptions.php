<?php

namespace Oveleon\ContaoOnofficeApiBundle;

final class ApiOptions extends Options
{
    protected function configure(): void
    {
        $this->set(self::MODE_READ, [
            'view',
            'addContactPerson',
            'contactPersonData',
            'savetemporary'
        ]);

        $this->set(self::MODE_CREATE, [
            'addContactPerson',
            'contactPersonData'
        ]);
    }
}
