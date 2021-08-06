<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use onOffice\SDK\onOfficeSDK;

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
            case 'estates':
                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'estate', ['data'=>$param]);
                break;
            case 'addresses':
                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'address', $param);
                break;
            case 'appointments':
                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'calendar', $param);
                break;
            case 'tasks':
                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'task', $param);
                break;
            case 'agentslogs':
                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'agentslog', $param);
                break;
            case 'relations':
                $data = $this->call(onOfficeSDK::ACTION_ID_CREATE, 'relation', $param);
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
