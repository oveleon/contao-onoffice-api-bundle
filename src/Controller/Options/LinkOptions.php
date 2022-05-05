<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller\Options;

final class LinkOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_UPLOAD, [
            'module',
            'title',
            'Art',
            'url',
            'relatedRecordId'
        ]);
    }
}
