<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use Contao\Folder;
use Contao\System;

/**
 * Provide methods to run automated jobs.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class OnOfficeAutomator extends System
{

	/**
	 * Make the constuctor public
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Purge the estate pictures cache
	 */
	public function purgeEstatePictureCache()
	{
        $objFolder = new Folder('assets/estatepictures');
        $objFolder->purge();

		// Add a log entry
		$this->log('Purged the onOffice image cache', __METHOD__, TL_CRON);
	}
}
