<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller\Options;

final class AuthenticatorOptions extends Options
{
    const MODE_AUTH_DATA = 121208;

    protected function configure(): void
    {
        $this->set(self::MODE_AUTH_DATA, [
            'key'
        ]);
    }
}
