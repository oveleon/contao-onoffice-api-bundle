<?php

namespace Oveleon\ContaoOnofficeApiBundle;

final class UserOptions extends Options
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

    protected function getName(): string
    {
        return 'user';
    }

    protected function getModes(): array
    {
        return [
            Options::MODE_READ
        ];
    }
}
