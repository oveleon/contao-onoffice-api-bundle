<?php

namespace Oveleon\ContaoOnofficeApiBundle;

final class EstatePictureOptions extends Options
{
    protected function configure(): void
    {
        $this->set(Options::MODE_READ, [
            'estateids',
            'categories' => [
                'Titelbild',
                'Foto',
                'Foto_gross',
                'Grundriss',
                'Lageplan',
                'Epass_Skala',
                'Panorama',
                'Link',
                'Film-Link',
                'Ogulo-Link',
                'Objekt-Link'
            ],
            'size',
            'language'
        ]);
    }
}
