<?php
/*
 * This file is part of Oveleon ContaoOnofficeApiBundle.
 *
 * (c) https://www.oveleon.de/
 */

$GLOBALS['TL_DCA']['tl_regions']['list']['global_operations']['importRegions'] = array(
    'href'                => 'key=importRegions',
    'class'               => 'header_theme_import'
);

// Add fields
$GLOBALS['TL_DCA']['tl_regions']['fields']['oid'] = array
(
    'exclude'                 => true,
    'search'                  => true,
    'sorting'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);
