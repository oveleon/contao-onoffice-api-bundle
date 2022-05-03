<?php

namespace Oveleon\ContaoOnofficeApiBundle;

final class CalendarOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_READ, [
            'datestart',
            'dateend',
            'showcancelled',
            'users',
            'groups',
            'allusers',
            'filter'
        ]);

        $this->set(Options::MODE_EDIT, [
            'relatedAddressIds',
            'relatedEstateId',
            'location' => [
                'estate',
                'user',
                'group',
                'mandant',
                'sonstiges',
                'customVideoUrl',
                'userMeetingUrl'
            ],
            'subscribers',
            'reminderTypes'
        ]);

        $this->set(Options::MODE_CREATE, [
           'relatedAddressIds',
           'relatedEstateId',
           'location' => [
               'estate',
               'user',
               'group',
               'mandant',
               'sonstiges',
               'customVideoUrl',
               'userMeetingUrl'
           ],
           'subscribers',
           'reminderTypes'
        ]);
    }

    protected function getName(): string
    {
        return 'calendar';
    }

    protected function getModes(): array
    {
        return [
            Options::MODE_CREATE,
            Options::MODE_EDIT
        ];
    }
}
