<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller\Options;

final class FileOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_EDIT, [
            'fileId',
            'language',
            'Art',
            'title',
            'freetext',
            'documentAttribute',
        ]);

        // Uploading files takes place in two steps. First, use the preparation
        // options to initialize a new file (then MODE_UPLOAD)
        $this->set(Options::MODE_PREPARE, [
            'data',
            'file'
        ]);

        $this->set(Options::MODE_UPLOAD, [
            'tmpUploadId',
            'relatedRecordId',
            'Art',
            'file',
            'title',
            'documentAttribute',
            'module',
            'position',
            'setDefaultPublicationRights',
        ]);
    }
}
