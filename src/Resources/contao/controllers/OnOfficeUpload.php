<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use onOffice\SDK\onOfficeSDK;

/**
 * onOffice upload file api controller.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class OnOfficeUpload extends OnOfficeHandler
{
    /**
     * Run the controller
     *
     * @param String $module    Plural name of onOffice module
     * @param int    $id        Id of onOffice module
     * @param array  $arrParam  Array of data parameters
     *
     * @return array
     */
    public function run($module, $id, $arrParam=array())
    {
        $arrValidModules = array('estates', 'addresses', 'agentslogs');

        if (!in_array($module, $arrValidModules))
        {
            return array('error' => 'Not existing module called'); // ToDo: Richtigen Status zurückgeben und Fehlercode im Header ergänzen.
        }

        $arrValidParam1 = array('data', 'file');
        $arrValidParam2 = array('file', 'Art', 'title', 'freetext', 'documentAttribute', 'relatedRecordId', 'estatelanguage', 'language', 'setDefaultPublicationRights');

        $param = $this->getValidParameters($arrValidParam1, $arrParam);
        $data = $this->call(onOfficeSDK::ACTION_ID_DO, 'uploadfile', $param);

        unset($param);

        $param = $this->getValidParameters($arrValidParam2, $arrParam);

        if (isset($param['Art']) && $param['Art'] === 'Titelbild')
        {
            $originalParam = $this->unsetTitleImageByRealEstateId($id);
            $param['title'] = $originalParam['title'] ?: $param['title'];
        }

        $param['module'] = $this->getModuleName($module);
        $param['tmpUploadId'] = $data['data']['records'][0]['elements']['tmpUploadId'];
        $param['relatedRecordId'] = $id;

        $data = $this->call(onOfficeSDK::ACTION_ID_DO, 'uploadfile', $param);

        return $data;
    }

    public function run2($module, $id, $arrParam=array())
    {
        $arrValidModules = array('estates', 'addresses', 'agentslogs');

        if (!in_array($module, $arrValidModules))
        {
            return array('error' => 'Not existing module called'); // ToDo: Richtigen Status zurückgeben und Fehlercode im Header ergänzen.
        }

        $arrValidParam1 = array('title', 'Art', 'url');

        $param = $this->getValidParameters($arrValidParam1, $arrParam);
        $param['module'] = $this->getModuleName($module);
        $param['relatedRecordId'] = $id;

        $data = $this->call(onOfficeSDK::ACTION_ID_DO, 'uploadfile', $param);

        return $data;
    }

    /**
     * Return parameters by POST method
     *
     * @param array $arrValidParam    Array of valid parameters
     * @param array $arrParam         Array of data parameters
     *
     * @return array
     */
    private function getValidParameters($arrValidParam, $arrParam)
    {
        $arrParam = !count($arrParam) ? $_POST : $arrParam;
        $param = array();

        foreach ($arrParam as $key => $value)
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
        if(!is_array($ids)){
            $ids = [$ids];
        }

        $param = [
            'estateids'  => $ids,
            'categories' => ['Titelbild'],
        ];

        $data = $this->call(onOfficeSDK::ACTION_ID_GET, 'estatepictures', $param);

        $fileId = $data['data']['records'][0]['id'];
        $info   = $data['data']['records'][0]['elements'][0];

        if($fileId)
        {
            $param = [
                'fileId'  => $fileId,
                'Art' => 'Foto',
            ];

            $data = $this->call(onOfficeSDK::ACTION_ID_MODIFY, 'file', $param);
        }

        return $info;
    }
}
