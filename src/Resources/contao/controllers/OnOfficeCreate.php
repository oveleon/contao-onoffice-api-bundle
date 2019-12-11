<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use onOffice\SDK\onOfficeSDK;
use Symfony\Component\HttpFoundation\JsonResponse;
use Oveleon\OnOfficeApi\ApiHandler;

/**
 * onOffice create api controller.
 *
 * @author Daniele Sciannimanica <daniele@oveleon.de>
 */
class OnOfficeCreate extends \Frontend
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
     *
     * @return JsonResponse
     */
    public function run($module)
    {
        $apiHandler = new ApiHandler();

        $apiHandler->setApiVersion('stable');
        $apiHandler->setAccessData(\Config::get('onOfficeApiToken'), \Config::get('onOfficeApiSecret'));

        switch ($module)
        {
            case 'appointment':
                $param = $this->getParameters();

                $data = $apiHandler->call(onOfficeSDK::ACTION_ID_CREATE, 'calendar', $param);
                break;
            case 'task':
                $param = $this->getParameters();

                $data = $apiHandler->call(onOfficeSDK::ACTION_ID_CREATE, 'task', $param);
                break;
            case 'agentslog':
                $param = $this->getParameters();

                $data = $apiHandler->call(onOfficeSDK::ACTION_ID_CREATE, 'agentslog', $param);
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