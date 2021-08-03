<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use onOffice\SDK\onOfficeSDK;

/**
 * onOffice edit api controller.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class OnOfficeEdit extends OnOfficeHandler
{
    /**
     * Run the controller
     *
     * @param String $module  Plural name of onOffice module
     * @param int    $id      Id of onOffice module
     *
     * @return array
     */
    public function run($module, $id, $arrParam=array())
    {
        $param = !count($arrParam) ? $this->getParameters() : $arrParam;

        switch ($module)
        {
            case 'estates':
                $data = $this->call(onOfficeSDK::ACTION_ID_MODIFY, onOfficeSDK::MODULE_ESTATE, ['data'=>$param], $id);
                break;
            case 'addresses':
                $data = $this->call(onOfficeSDK::ACTION_ID_MODIFY, onOfficeSDK::MODULE_ADDRESS, $param, $id);
                break;
            case 'files':
                $param['fileId'] = $id;

                $data = $this->call(onOfficeSDK::ACTION_ID_MODIFY, 'file', $param, $id);
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
