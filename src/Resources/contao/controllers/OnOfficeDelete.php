<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use onOffice\SDK\onOfficeSDK;

/**
 * onOffice delete api controller.
 *
 * @author Fabian Ekert <https://github.com/eki89>
 */
class OnOfficeDelete extends OnOfficeHandler
{
    /**
     * Run the controller
     *
     * @param String $strModule  Plural name of onOffice module
     * @param array  $arrParam   Array of parameters
     *
     * @return array
     */
    public function run($strModule, $arrParam=array())
    {
        $param = !count($arrParam) ? $this->getParameters() : $arrParam;

        switch ($strModule)
        {
            case 'files':
                $data = $this->call(onOfficeSDK::ACTION_ID_DELETE, 'file', $param);
                break;
        }

        return $data;
    }

    /**
     * Return parameters by POST method
     *
     * @return array
     */
    private function getParameters()
    {
        $param = array();

        foreach ($_POST as $key => $value)
        {
            $param[$key] = $value;
        }

        return $param;
    }
}
