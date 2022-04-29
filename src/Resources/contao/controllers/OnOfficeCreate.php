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
     * @return JsonResponse|array
     */
    public function run($module, ?array $arrDefaultParam=null, bool $asArray=false)
    {
        $param = $this->getParameters();

        if(null !== $arrDefaultParam)
        {
            $param = array_merge_recursive($arrDefaultParam, $param);
        }

        switch ($module)
        {
            case 'estate':
                $options = (new EstateOptions(Options::MODE_CREATE))->validate($param,  true);

                //$data = $this->call(onOfficeSDK::ACTION_ID_CREATE, onOfficeSDK::MODULE_ESTATE, $options);

                if(array_key_exists('addContactPerson', $param))
                {
                    //$objRead = new OnOfficeRead();

                    /*$objRead->run('search', onOfficeSDK::MODULE_ADDRESS, null, [
                        'includecontactdata' => 1
                    ]);*/
                }


                break;
            case 'appointment':
                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'calendar', $param);
                break;
            case 'task':
                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'task', $param);
                break;
            case 'agentslog':
                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'agentslog', $param);
                break;
        }

        if ($asArray)
        {
            return $data;
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
