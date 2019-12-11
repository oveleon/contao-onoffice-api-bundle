<?php
/*
 * This file is part of Oveleon ContaoOnofficeApiBundle.
 *
 * (c) https://www.oveleon.de/
 */

$GLOBALS['TL_DCA']['tl_onoffice_api_view'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'alias' => 'unique'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 2,
            'fields'                  => array('alias DESC'),
            'flag'                    => 1,
            'panelLayout'             => 'filter;sort,search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('type', 'title', 'alias'),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.svg'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['toggle'],
                'icon'                => 'visible.svg',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('tl_onoffice_api_view', 'toggleIcon')
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('type', 'protected'),
        'default'                     => '{type_legend},type',
        'estate'                      => '{type_legend},type;{title_legend},title,alias;{data_legend},data;{filter_legend},filter;{sortby_legend},sortby;{limit_offset_legend},listlimit,listoffset;{language_legend:hide},estatelanguage,outputlanguage;{expert_legend:hide},filterid,formatoutput,addContactPerson;{protected_legend:hide},onOfficeGroup,protected;{publish_legend},published',
        'address'                     => '{type_legend},type;{title_legend},title,alias;{data_legend},data;{recordids_legend},recordids;{limit_offset_legend},listlimit,listoffset;{language_legend:hide},outputlanguage;{expert_legend:hide},filterid,formatoutput;{protected_legend:hide},onOfficeGroup,protected;{publish_legend},published',
        'agentslog'                   => '{type_legend},type;{title_legend},title,alias;{data_legend},data;{estateid_legend},estateid;{limit_offset_legend},listlimit;{expert_legend:hide},tracking;{protected_legend:hide},onOfficeGroup,protected;{publish_legend},published',
        'user'                        => '{type_legend},type;{title_legend},title,alias;{data_legend},data;{filter_legend},filter;{sortby_legend},sortby;{limit_offset_legend},listlimit;{protected_legend:hide},onOfficeGroup,protected;{publish_legend},published',
        'estatepicture'               => '{type_legend},type;{title_legend},title,alias;{estateids_legend},estateids;{categories_legend},categories;{language_legend:hide},language;{expert_legend:hide},size;{protected_legend:hide},onOfficeGroup,protected;{publish_legend},published',
        'searchcriteria'              => '{type_legend},type;{title_legend},title,alias;{ids_legend},ids;{mode_legend},mode;{protected_legend:hide},onOfficeGroup,protected;{publish_legend},published',
    ),

    // Subpalettes
    'subpalettes' => array
    (
        'protected'                   => 'groups'
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
        'type' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['type'],
            'default'                 => 'estate',
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'inputType'               => 'select',
            'options'                 => array('estate', 'address', 'agentslog', 'user', 'estatepicture', 'searchcriteria'),
            'eval'                    => array('helpwizard'=>true, 'chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['title'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
            'sql'                     => "varchar(128) NOT NULL default ''"
        ),
        'alias' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['alias'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
            'save_callback' => array
            (
                array('tl_onoffice_api_view', 'generateAlias')
            ),
            'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
        ),
        'data' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['data'],
            'exclude'                 => true,
            'inputType'               => 'checkboxWizard',
            'options_callback'        => array('tl_onoffice_api_view', 'getModuleFields'),
            'eval'                    => array('multiple'=>true),
            'sql'                     => "blob NULL"
        ),
        'categories' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['categories'],
            'exclude'                 => true,
            'inputType'               => 'checkboxWizard',
            'options'                 => array('Titelbild', 'Foto', 'Foto_gross', 'Grundriss', 'Lageplan', 'Epass_Skala', 'Panorama'),
            'eval'                    => array('multiple'=>true),
            'sql'                     => "blob NULL"
        ),
        'recordids'  => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['recordids'],
            'inputType' 	            => 'multiColumnWizard',
            'eval' 			            => array
            (
                'columnFields' => array
                (
                    'id' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['id'],
                        'inputType'             => 'text',
                        'eval' 			        => array('style'=>'width:100%')
                    )
                )
            ),
            'sql'                     => "blob NULL"
        ),
        'estateids'  => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['estateids'],
            'inputType' 	            => 'multiColumnWizard',
            'eval' 			            => array
            (
                'columnFields' => array
                (
                    'id' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['id'],
                        'inputType'             => 'text',
                        'eval' 			        => array('style'=>'width:100%')
                    )
                )
            ),
            'sql'                     => "blob NULL"
        ),
        'ids'  => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['ids'],
            'inputType' 	            => 'multiColumnWizard',
            'eval' 			            => array
            (
                'columnFields' => array
                (
                    'id' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['id'],
                        'inputType'             => 'text',
                        'eval' 			        => array('style'=>'width:100%')
                    )
                )
            ),
            'sql'                     => "blob NULL"
        ),
        'filter'  => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['filter'],
            'inputType' 	            => 'multiColumnWizard',
            'eval' 			            => array
            (
                'columnFields' => array
                (
                    'field' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['field'],
                        'inputType'             => 'text',
                        'eval' 			        => array('style'=>'width:100%')
                    ),
                    'op' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['op'],
                        'inputType'             => 'select',
                        'options'               => array('equal', 'greater', 'less', 'greater_equal', 'less_equal', 'not_equal', 'compare', 'between', 'like', 'not_like', 'in', 'not_in'),
                        'eval' 			        => array('style'=>'width:100%')
                    ),
                    'val' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['val'],
                        'inputType'             => 'text',
                        'eval' 			        => array('style'=>'width:100%')
                    )
                )
            ),
            'sql'                     => "blob NULL"
        ),
        'mode' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['mode'],
            'default'                 => 'estate',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('internal', 'external', 'searchcriteria'),
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(32) NOT NULL default ''"
        ),
        'sortby'  => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['sortby'],
            'inputType' 	            => 'multiColumnWizard',
            'eval' 			            => array
            (
                'columnFields' => array
                (
                    'field' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['field'],
                        'inputType'             => 'text',
                        'eval' 			        => array('style'=>'width:100%')
                    ),
                    'sorting' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['sorting'],
                        'inputType'             => 'select',
                        'options'               => array('ASC', 'DESC'),
                        'eval' 			        => array('style'=>'width:100%')
                    )
                )
            ),
            'sql'                     => "blob NULL"
        ),
        'filterid' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['filterid'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
            'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
        ),
        'estateid' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['estateid'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
            'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
        ),
        'listlimit' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['listlimit'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
            'sql'                     => "smallint(5) unsigned NOT NULL default '20'"
        ),
        'listoffset' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['listoffset'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
            'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
        ),
        'formatoutput' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['formatoutput'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'addContactPerson' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['addContactPerson'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'estatelanguage' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['estatelanguage'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('DEU', 'ENG'),
            'eval'                    => array('includeBlankOption'=>true, 'maxlength'=>8, 'tl_class'=>'w50'),
            'sql'                     => "varchar(8) NOT NULL default ''"
        ),
        'outputlanguage' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['outputlanguage'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('DEU', 'ENG'),
            'eval'                    => array('includeBlankOption'=>true, 'maxlength'=>8, 'tl_class'=>'w50'),
            'sql'                     => "varchar(8) NOT NULL default ''"
        ),
        'language' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['language'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('DEU', 'ENG'),
            'eval'                    => array('includeBlankOption'=>true, 'maxlength'=>8, 'tl_class'=>'w50'),
            'sql'                     => "varchar(8) NOT NULL default ''"
        ),
        'tracking' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['tracking'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'size' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['size'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>64, 'tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'onOfficeGroup' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['onOfficeGroup'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
            'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
        ),
        'protected' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['protected'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'groups' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['groups'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'foreignKey'              => 'tl_member_group.name',
            'eval'                    => array('mandatory'=>true, 'multiple'=>true),
            'sql'                     => "blob NULL",
            'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
        ),
        'published' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_api_view']['published'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('doNotCopy'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        )
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class tl_onoffice_api_view extends Backend
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
     * Auto-generate an api view alias if it has not been set yet
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return string
     *
     * @throws Exception
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate an alias if there is none
        if ($varValue == '')
        {
            $autoAlias = true;
            $varValue = StringUtil::generateAlias($dc->activeRecord->title);
        }

        $objAlias = $this->Database->prepare("SELECT id FROM tl_onoffice_api_view WHERE id=? OR alias=?")
            ->execute($dc->id, $varValue);

        // Check whether the page alias exists
        if ($objAlias->numRows > 1)
        {
            if (!$autoAlias)
            {
                throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
            }

            $varValue .= '-' . $dc->id;
        }

        return $varValue;
    }

    /**
     * Return the "toggle visibility" button
     *
     * @param array  $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (\strlen(Input::get('tid')))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->hasAccess('tl_onoffice_api_view::published', 'alexf'))
        {
            return '';
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published'])
        {
            $icon = 'invisible.svg';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"').'</a> ';
    }

    /**
     * Toggle the visibility of an api view
     *
     * @param integer       $intId
     * @param boolean       $blnVisible
     * @param DataContainer $dc
     *
     * @throws Contao\CoreBundle\Exception\AccessDeniedException
     */
    public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
    {
        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

        if ($dc)
        {
            $dc->id = $intId; // see #8043
        }

        // Trigger the onload_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_onoffice_api_view']['config']['onload_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_onoffice_api_view']['config']['onload_callback'] as $callback)
            {
                if (\is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (\is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }

        // Check the field access
        if (!$this->User->hasAccess('tl_onoffice_api_view::published', 'alexf'))
        {
            throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish onOffice api view ID "' . $intId . '".');
        }

        // Set the current record
        if ($dc)
        {
            $objRow = $this->Database->prepare("SELECT * FROM tl_onoffice_api_view WHERE id=?")
                ->limit(1)
                ->execute($intId);

            if ($objRow->numRows)
            {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new Versions('tl_onoffice_api_view', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_onoffice_api_view']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_onoffice_api_view']['fields']['published']['save_callback'] as $callback)
            {
                if (\is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                }
                elseif (\is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        // Update the database
        $this->Database->prepare("UPDATE tl_onoffice_api_view SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
            ->execute($intId);

        if ($dc)
        {
            $dc->activeRecord->tstamp = $time;
            $dc->activeRecord->published = ($blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_onoffice_api_view']['config']['onsubmit_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_onoffice_api_view']['config']['onsubmit_callback'] as $callback)
            {
                if (\is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (\is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }

    /**
     * Return all module fields
     *
     * @return array
     */
    public function getModuleFields($dc)
    {
        $controller = new \Oveleon\ContaoOnofficeApiBundle\OnOfficeRead();

        $entityDetails = $controller->run('fields', $dc->activeRecord->type, null, array('labels'=>true),true);

        $fields = array();

        foreach ($entityDetails['data']['records'][0]['elements'] as $field => $value)
        {
            if ($field === 'label')
            {
                continue;
            }

            $key = $value['label'] . ' ('.$field.')';
            $fields[$key] = $field;
        }

        ksort($fields);

        return array_flip($fields);
    }
}
