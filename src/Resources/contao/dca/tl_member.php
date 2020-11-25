<?php

/*
 * This file is part of Oveleon ContaoOnofficeShopTv Bundle.
 *
 * (c) https://www.oveleon.de/
 */

// Extend the default palette
Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('onoffice_legend', 'login_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addField(array('onOfficeUsername', 'onOfficeUserId', 'onOfficeGroup', 'onOfficeBranchGroup'), 'onoffice_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_member')
;

// Add fields to tl_member
$GLOBALS['TL_DCA']['tl_member']['fields']['onOfficeGroup'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['onOfficeGroup'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
    'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['onOfficeBranchGroup'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['onOfficeBranchGroup'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_member_onoffice', 'getMemberBranchGroups'),
    'eval'                    => array('chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['onOfficeUsername'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['onOfficeUsername'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['onOfficeUserId'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['onOfficeUserId'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['groups']['options_callback'] = array('tl_member_onoffice', 'getMemberGroups');


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class tl_member_onoffice extends Backend
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
     * Return an array of member branch groups
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getMemberBranchGroups(DataContainer $dc)
    {
        $objMemberGroups = $this->Database->execute("SELECT id, name FROM tl_member_group WHERE onOfficeBranchGroup=1");

        if ($objMemberGroups->numRows < 1)
        {
            return array();
        }

        $return = array();

        while ($objMemberGroups->next())
        {
            $return[$objMemberGroups->id] = $objMemberGroups->name;
        }

        return $return;
    }

    /**
     * Return all member groups
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getMemberGroups(DataContainer $dc)
    {
        $objMemberGroups = $this->Database->execute("SELECT id, name, onOfficeGroup, onOfficeBranchGroup, filterid FROM tl_member_group");

        if ($objMemberGroups->numRows < 1)
        {
            return array();
        }

        $return = array();

        while ($objMemberGroups->next())
        {
            $label = $objMemberGroups->name;

            if ($objMemberGroups->onOfficeGroup || $objMemberGroups->onOfficeBranchGroup)
            {
                $label .= '<span style="display:inline;color: #999"> [';

                if ($objMemberGroups->onOfficeGroup)
                {
                    $label .= 'Gruppe: ' . $objMemberGroups->onOfficeGroup;
                }

                if ($objMemberGroups->onOfficeGroup && $objMemberGroups->onOfficeBranchGroup)
                {
                    $label .= ', ';
                }

                if ($objMemberGroups->onOfficeBranchGroup)
                {
                    $label .= 'Standortgruppe, FilterID: ' .$objMemberGroups->filterid;
                }

                $label .= ']</span>';
            }

            $return[$objMemberGroups->id] = $label;
        }

        return $return;
    }
}
