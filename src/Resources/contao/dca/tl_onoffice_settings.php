<?php
/*
 * This file is part of Oveleon ContaoOnofficeApiBundle.
 *
 * (c) https://www.oveleon.de/
 */

$GLOBALS['TL_DCA']['tl_onoffice_settings'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'File',
		'closed'                      => true
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{api_authentication_legend},onOfficeApiSecret,onOfficeApiToken,onOfficeApiVersion;'
	),

	// Fields
	'fields' => array
	(
		'onOfficeApiSecret' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_settings']['onOfficeApiSecret'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50')
		),
		'onOfficeApiToken' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_settings']['onOfficeApiToken'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50')
		),
		'onOfficeApiVersion' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_onoffice_settings']['onOfficeApiVersion'],
			'inputType'               => 'text',
			'default'                 => 'stable',
			'eval'                    => array('tl_class'=>'w50', 'rgxp'=>'digit')
		)
	)
);
