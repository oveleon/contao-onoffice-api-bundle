<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use onOffice\SDK\onOfficeSDK;
use Symfony\Component\HttpFoundation\JsonResponse;
use Oveleon\OnOfficeSDK\ApiHandler;

/**
 * onOffice edit api controller.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class OnOfficeEdit extends \Frontend
{

    /**
     * Initialize the object
     */
    public function __construct()
    {
        // Load the user object before calling the parent constructor
        $this->import('FrontendUser', 'User');
        parent::__construct();

        // Check whether a user is logged in
        \define('BE_USER_LOGGED_IN', $this->getLoginStatus('BE_USER_AUTH'));
        \define('FE_USER_LOGGED_IN', $this->getLoginStatus('FE_USER_AUTH'));
    }

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
        $apiHandler = new ApiHandler();

        $apiHandler->setApiVersion('stable');
        $apiHandler->setAccessData(\Config::get('onOfficeApiToken'), \Config::get('onOfficeApiSecret'));

        switch ($module)
        {
            case 'estates':
                $param['data'] = $this->getParameters();

                $data = $apiHandler->call(onOfficeSDK::ACTION_ID_MODIFY, onOfficeSDK::MODULE_ESTATE, $param, $id);
                break;
            case 'addresses':
                $param = $this->getParameters();

                $data = $apiHandler->call(onOfficeSDK::ACTION_ID_MODIFY, onOfficeSDK::MODULE_ADDRESS, $param, $id);
                break;
            case 'files':
                $param = $this->getParameters();

                $param['fileId'] = $id;

                $data = $apiHandler->call(onOfficeSDK::ACTION_ID_MODIFY, 'file', $param, $id);
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
