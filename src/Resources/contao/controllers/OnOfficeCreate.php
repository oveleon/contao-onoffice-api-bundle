<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use onOffice\SDK\onOfficeSDK;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * onOffice create api controller.
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class OnOfficeCreate extends OnOfficeHandler
{
    /**
     * Run the controller
     *
     * @param String $module  Plural name of onOffice module
     *
     * @return JsonResponse
     */
    public function run($module)
    {
        switch ($module)
        {
            case 'appointment':
                $param = $this->getParameters();

                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'calendar', $param);
                break;
            case 'task':
                $param = $this->getParameters();

                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'task', $param);
                break;
            case 'agentslog':
                $param = $this->getParameters();

                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'agentslog', $param);
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
