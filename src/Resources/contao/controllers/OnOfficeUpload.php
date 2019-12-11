<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use onOffice\SDK\onOfficeSDK;
use Symfony\Component\HttpFoundation\JsonResponse;
use Oveleon\OnOfficeApi\ApiHandler;

/**
 * onOffice upload file api controller.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class OnOfficeUpload extends \Frontend
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

        $arrValidModules = array('estates', 'addresses', 'agentslogs');

        if (!in_array($module, $arrValidModules))
        {
            return new JsonResponse(array('error' => 'Not existing module called')); // ToDo: Richtigen Status zurückgeben und Fehlercode im Header ergänzen.
        }

        $arrValidParam1 = array('data', 'file');
        $arrValidParam2 = array('file', 'Art', 'title', 'freetext', 'documentAttribute', 'relatedRecordId', 'estatelanguage', 'language');

        $param = $this->getParameters($arrValidParam1);
        $data = $apiHandler->call(onOfficeSDK::ACTION_ID_DO, 'uploadfile', $param);

        unset($param);

        $param = $this->getParameters($arrValidParam2);

        if (isset($param['Art']) && $param['Art'] === 'Titelbild')
        {
            $originalParam = $this->unsetTitleImageByRealEstateId($id);
            $param['title'] = $originalParam['title'] ?: $param['title'];
        }

        $param['module'] = $this->getModuleName($module);
        $param['tmpUploadId'] = $data['data']['records'][0]['elements']['tmpUploadId'];
        $param['relatedRecordId'] = $id;

        $data = $apiHandler->call(onOfficeSDK::ACTION_ID_DO, 'uploadfile', $param);

        return new JsonResponse($data);
    }

    /**
     * Return parameters by POST method
     *
     * @param array $arrValidParam    Array of valid parameters
     *
     * @return array
     */
    private function getParameters($arrValidParam)
    {
        $param = array();

        foreach ($_POST as $key => $value)
        {
            if ($key === 'data')
            {
                $value = str_replace('data:image/jpeg;base64,', '', $value);
            }

            if ($key === 'file' && strpos($value, '[TIME]') !== false )
            {
                $value = str_replace('[TIME]', time(), $value);
            }

            if (in_array($key, $arrValidParam))
            {
                $param[$key] = $value;
            }
        }

        return $param;
    }

    /**
     * Return actual onOffice module string by module plural name
     *
     * @param String $module  Plural name of onOffice module
     *
     * @return array
     */
    private function getModuleName($module)
    {
        $mapper = array
        (
            'estates' => onOfficeSDK::MODULE_ESTATE,
            'addresses' => onOfficeSDK::MODULE_ADDRESS,
            'agentslogs' => 'agentslog',
        );

        return $mapper[$module];
    }

    /**
     * Change current title image to normal picture
     *
     * @param $ids
     * @return array
     */
    private function unsetTitleImageByRealEstateId($ids)
    {
        $apiHandler = new ApiHandler();

        $apiHandler->setApiVersion('stable');
        $apiHandler->setAccessData(\Config::get('onOfficeApiToken'), \Config::get('onOfficeApiSecret'));

        if(!is_array($ids)){
            $ids = [$ids];
        }

        $param = [
            'estateids'  => $ids,
            'categories' => ['Titelbild'],
        ];

        $data = $apiHandler->call(onOfficeSDK::ACTION_ID_GET, 'estatepictures', $param);

        $fileId = $data['data']['records'][0]['id'];
        $info   = $data['data']['records'][0]['elements'][0];

        if($fileId)
        {
            $param = [
                'fileId'  => $fileId,
                'Art' => 'Foto',
            ];

            $data = $apiHandler->call(onOfficeSDK::ACTION_ID_MODIFY, 'file', $param);
        }

        return $info;
    }
}