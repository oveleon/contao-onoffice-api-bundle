<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller\Options;

final class RelationOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_CREATE, [
            'parentid',
            'childid',
            'relationtype',
            'relationinfo'
        ]);

        $this->set(Options::MODE_EDIT, [
            'parentid',
            'childid',
            'relationtype',
            'relationinfo'
        ]);

        $this->set(Options::MODE_DELETE, [
            'parentid',
            'childid',
            'relationtype'
        ]);
    }
}
