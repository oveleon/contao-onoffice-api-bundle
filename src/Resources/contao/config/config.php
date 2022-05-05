<?php

/*
 * This file is part of Oveleon OnOfficeApi Bundle.
 *
 * (c) https://www.oveleon.de/
 */

// Back end modules
array_insert($GLOBALS['BE_MOD'], 1, array
(
    'onoffice' => array
    (
        'onoffice_api_view' => array
        (
            'tables'      => array('tl_onoffice_api_view')
        ),
        'onoffice_auth' => array
        (
            'tables'      => array('tl_onoffice_auth')
        ),
        'onoffice_settings' => array
        (
            'tables'      => array('tl_onoffice_settings')
        )
    )
));

// Models
$GLOBALS['TL_MODELS']['tl_onoffice_api_view'] = 'Oveleon\ContaoOnofficeApiBundle\OnofficeApiViewModel';
$GLOBALS['TL_MODELS']['tl_onoffice_auth'] = 'Oveleon\ContaoOnofficeApiBundle\OnofficeAuthModel';

// Purge jobs
array_insert($GLOBALS['TL_PURGE']['folders'], 1, array
(
    'estatepictures' => array
    (
        'callback' => array('Oveleon\ContaoOnofficeApiBundle\OnOfficeAutomator', 'purgeEstatePictureCache'),
        'affected' => array('assets/estatepictures')
    )
));

// Add permissions
$GLOBALS['TL_PERMISSIONS'][] = 'regions';

// Style sheet
if (TL_MODE == 'BE')
{
    $GLOBALS['TL_CSS'][] = 'bundles/contaoonofficeapi/style.css|static';
}
