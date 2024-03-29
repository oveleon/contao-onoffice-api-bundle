<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller\Options;

final class FieldOptions extends Options
{
    protected function configure(): void
    {
        $this->set(self::MODE_READ, [
            'labels',
            'language',
            'fieldList',
            'modules',
            'showOnlyInactive',
            'realDataTypes',
            'showFieldMeasureFormat'
        ]);
    }
}
