<?php

namespace Oveleon\ContaoOnofficeApiBundle;

final class AddressOptions extends Options
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

        $this->set(Options::MODE_CREATE, [
            'phone',
            'phone_private',
            'phone_business',
            'mobile',
            'default_phone',
            'fax',
            'fax_private',
            'fax_business',
            'default_fax',
            'email',
            'email_business',
            'email_private',
            'default_email',
            'Benutzer',
            'Status',
            'newsletter_aktiv',
            'Land',
            'checkDuplicate',
            'noOverrideByDuplicate'
        ]);

        // The transfer of the fields to onOffice in `create` mode is inconsistent. Therefore, fields that
        // are retrieved at the beginning must be appended to the root instead of the usual `data` node.
        $this->registerModesCallback(
            function(int $mode, ?array $data)
            {
                switch($mode)
                {
                    case Options::MODE_CREATE:
                        $this->set($mode, $data);
                        break;
                    default:
                        $this->set($mode, ['data' => $data]);
                }
            }
        );
    }

    protected function getName(): string
    {
       return 'address';
    }

    protected function getModes(): array
    {
        return [
            Options::MODE_CREATE,
            Options::MODE_READ,
            Options::MODE_EDIT
        ];
    }
}
