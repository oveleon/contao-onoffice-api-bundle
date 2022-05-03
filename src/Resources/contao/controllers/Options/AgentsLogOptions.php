<?php

namespace Oveleon\ContaoOnofficeApiBundle;

class AgentsLogOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_READ, [
            'estateid',
            'addressid',
            'filter',
            'listlimit',
            'listoffset',
            'sortby',
            'sortorder',
            'fullmail',
            'tracking'
        ]);

        $this->set(Options::MODE_CREATE, [
            'datetime',
            'actionkind',
            'actiontype',
            'origincontact',
            'feature',
            'cost',
            'duration',
            'advisorylevel',
            'reasoncancellation',
            'note',
            'addressids',
            'estateid',
            'projectid',
            'taskid',
            'appointmentid',
            'fileids',
            'userid'
        ]);
    }

    protected function setName(): void
    {
        $this->name = 'agentsLog';
    }

    protected function setModes(): void
    {
        $this->modes = [
            Options::MODE_READ
        ];
    }
}
