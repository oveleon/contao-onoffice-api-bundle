<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use onOffice\SDK\onOfficeSDK;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @return JsonResponse
     */
    public function run($module, $id)
    {
        switch ($module)
        {
            case 'estates':
                $param['data'] = $this->getParameters();

                $data = $this->call(onOfficeSDK::ACTION_ID_MODIFY, onOfficeSDK::MODULE_ESTATE, $param, $id);
                break;
            case 'addresses':
                $param = $this->getParameters();

                $data = $this->call(onOfficeSDK::ACTION_ID_MODIFY, onOfficeSDK::MODULE_ADDRESS, $param, $id);
                break;
            case 'files':
                $param = $this->getParameters();

                $param['fileId'] = $id;

                $data = $this->call(onOfficeSDK::ACTION_ID_MODIFY, 'file', $param, $id);
                break;
        }

        return new JsonResponse($data);
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
