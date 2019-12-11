<?php

/*
 * This file is part of Oveleon Contao onOffice API Bundle.
 *
 * (c) https://www.oveleon.de/
 */

$GLOBALS['TL_DCA']['tl_member_group']['list']['label']['label_callback'] = array('tl_member_group_onoffice', 'addIconAndDetails');

// Extend the default palette
Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('onoffice_legend', 'redirect_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addField(array('onOfficeGroup', 'filterid', 'filteridAddress', 'onOfficeBranchGroup'), 'onoffice_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_member_group')
;

// Add fields to tl_member_group
$GLOBALS['TL_DCA']['tl_member_group']['fields']['onOfficeGroup'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['onOfficeGroup'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
    'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_member_group']['fields']['onOfficeBranchGroup'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['onOfficeBranchGroup'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50 m12 clr'),
    'sql'                     => "char(1) NOT NULL default ''",
    'save_callback' => array
    (
        array('tl_member_group_onoffice', 'checkMemberInBranchGroup')
    ),
);

$GLOBALS['TL_DCA']['tl_member_group']['fields']['filterid'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['filterid'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
    'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_member_group']['fields']['filteridAddress'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['filteridAddress'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
    'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 * @author Daniele Sciannimanica <daniele@oveleon.de>
 */
class tl_member_group_onoffice extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Add an image to each record
     *
     * @param array  $row
     * @param string $label
     *
     * @return string
     */
    public function addIconAndDetails($row, $label)
    {
        $image = 'mgroup';
        $time = \Date::floorToMinute();

        $disabled = ($row['start'] !== '' && $row['start'] > $time) || ($row['stop'] !== '' && $row['stop'] < $time);

        if ($row['disable'] || $disabled)
        {
            $image .= '_';
        }

        if ($row['onOfficeGroup'] || $row['onOfficeBranchGroup'])
        {
            $label .= '<span style="color: #999"> [';

            if ($row['onOfficeGroup'])
            {
                $label .= 'Gruppe: ' .$row['onOfficeGroup'];
            }

            if ($row['onOfficeGroup'] && $row['onOfficeBranchGroup'])
            {
                $label .= ', ';
            }

            if ($row['onOfficeBranchGroup'])
            {
                $label .= 'Standortgruppe, FilterID: ' .$row['filterid'];
            }

            $label .= ']</span>';
        }

        return sprintf('<div class="list_icon" style="background-image:url(\'%ssystem/themes/%s/icons/%s.svg\')" data-icon="%s.svg" data-icon-disabled="%s.svg">%s</div>', TL_ASSETS_URL, Backend::getTheme(), $image, $disabled ? $image : rtrim($image, '_'), rtrim($image, '_') . '_', $label);
    }

    /**
     * Make sure there is no Member in Branch on disable
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function checkMemberInBranchGroup($varValue, DataContainer $dc)
    {
        $objMember = $this->Database->prepare("SELECT id FROM tl_member WHERE onOfficeBranchGroup=?")
            ->execute($dc->activeRecord->id);

        if ($objMember->numRows && $varValue == '')
        {
            throw new Exception($GLOBALS['TL_LANG']['ERR']['onOfficeBranchGroupMandatoryRequired']);
        }

        return $varValue;
    }
}
