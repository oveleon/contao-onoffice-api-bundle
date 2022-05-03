<?php

namespace Oveleon\ContaoOnofficeApiBundle;

final class TaskOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_READ, [
            'filter',
            'listlimit',
            'relatedAddressId',
            'relatedEstateId',
            'relatedProjectIds',
            'responsibilityByGroup'
        ]);

        $this->set(Options::MODE_CREATE, [
           'relatedAddressId',
           'relatedEstateId',
           'relatedProjectIds',
           'responsibilityByGroup'
        ]);

        $this->set(Options::MODE_EDIT, [
           'relatedAddressId',
           'relatedEstateId',
           'relatedProjectIds',
           'responsibilityByGroup'
        ]);
    }

    protected function getName(): string
    {
        return 'task';
    }

    protected function getModes(): array
    {
        return [
            Options::MODE_READ,
            Options::MODE_EDIT,
            Options::MODE_CREATE,
        ];
    }
}
