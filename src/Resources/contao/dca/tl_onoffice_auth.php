<?php
/*
 * This file is part of Oveleon ContaoOnofficeApiBundle.
 *
 * (c) https://www.oveleon.de/
 */

$GLOBALS['TL_DCA']['tl_onoffice_auth'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'switchToEdit'                => true,
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'key' => 'unique'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 0,
            'flag'                    => 1
        ),
        'label' => array
        (
            'fields'                  => array('key', 'restrictIp', 'restrictHost'),
            'showColumns'             => true
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_onoffice_auth']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_onoffice_auth']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_onoffice_auth']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('restrictIp', 'restrictHost'),
        'default'                     => 'key;{restrict_legend},restrictIp,restrictHost;',
    ),

    // Subpalettes
    'subpalettes' => array
    (
        'restrictIp'                   => 'allowedIps',
        'restrictHost'                 => 'allowedHosts'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'key' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_auth']['key'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
            'sql'                     => "varchar(128) NOT NULL default ''"
        ),
        'restrictIp' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_auth']['restrictIp'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'restrictHost' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_auth']['restrictHost'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'allowedIps' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_auth']['allowedIps'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'listWizard',
            'eval'                    => array('tl_class'=>'clr', 'mandatory' => true),
            'sql'                     => "blob NULL"
        ),
        'allowedHosts' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_auth']['allowedHosts'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'listWizard',
            'eval'                    => array('tl_class'=>'clr', 'mandatory' => true),
            'sql'                     => "blob NULL"
        )
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class tl_onoffice_auth extends Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }
}
