<?php

namespace Oveleon\ContaoOnofficeApiBundle;

/**
 * Fieldset class
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class Fieldset
{
    /**
     * Object instance (Singleton)
     * @var Fieldset
     */
    protected static $objInstance;

    /**
     * The fieldset data
     * @var array
     */
    protected static $arrData = array();

    /**
     * Valid resource types
     * @var array
     */
    protected static $arrValidRecourseTypes = array(
        'fields',
        'searchcriteriafields'
    );

    /**
     * Show Labels
     * @var array
     */
    public static $arrParameter = array(
        'labels' => true
    );

    /**
     * Instantiate the Fieldset object
     *
     * @return Fieldset The object instance
     */
    public static function getInstance()
    {
        if (static::$objInstance === null)
        {
            static::$objInstance = new static();
        }

        return static::$objInstance;
    }

    /**
     * Check whether a key is set
     *
     * @param string $resourceType The fieldset resource type
     * @param string $module
     *
     * @return boolean True if the key is set
     */
    public static function has($resourceType, $module='')
    {
        if($module)
        {
            return isset(static::$arrData[$resourceType][$module]);
        }

        return isset(static::$arrData[$resourceType]);
    }

    /**
     * Return a module entry
     *
     * @param string $resourceType The fieldset resource type
     * @param null $arrModules (only $resourceType: 'fields')
     *
     * @return mixed The fieldset data
     */
    public static function get($resourceType, $arrModules = null)
    {
        $data = null;
        $moduleQueue = null;
        $forceCall = false;

        if(static::has($resourceType))
        {
            if($arrModules !== null && $resourceType == 'fields')
            {
                foreach ($arrModules as $module)
                {
                    if(static::has($resourceType, $module))
                    {
                        // module already exists
                        $data[ $module ] = static::$arrData[$resourceType][$module];
                    }
                    else
                    {
                        // set to queue
                        $moduleQueue[] = $module;
                    }
                }
            }
            else
            {
                return static::$arrData[$resourceType];
            }
        }
        else
        {
            $moduleQueue = $arrModules;
            $forceCall = true;
        }

        if($moduleQueue !== null || $forceCall)
        {
            // call and set non existing modules
            static::set($resourceType, static::call($resourceType, $moduleQueue));

            if($moduleQueue !== null)
            {
                foreach ($arrModules as $module)
                {
                    $data[ $module ] = static::$arrData[$resourceType][$module];
                }
            }
            else
            {
                return static::$arrData[$resourceType];
            }
        }

        return $data;
    }

    /**
     * Add and restructure a fieldset entry from api response
     *
     * @param string $resourceType The fieldset resource type
     * @param mixed  $varValue The data to be set
     */
    public static function set($resourceType, $varValue)
    {
        if(!$varValue || !static::valid($resourceType))
        {
            return;
        }

        $arrValue = array();

        switch($resourceType)
        {
            case 'fields':

                foreach ($varValue['records'] as $index => $module)
                {
                    $arrValue[ $module['id'] ] = $module;
                }

                break;

            default:
                $arrValue = $varValue['records'];
        }

        static::$arrData[$resourceType] = $arrValue;
    }

    /**
     * Remove a fieldset entry
     *
     * @param string $resourceType The fieldset resource type
     * @param string $module
     */
    public static function remove($resourceType, $module='')
    {
        if($module)
        {
            unset(static::$arrData[$resourceType][$module]);
        }

        unset(static::$arrData[$resourceType]);
    }

    /**
     * Check is resourceType valid
     *
     * @param string $resourceType The fieldset resource type
     *
     * @return bool
     */
    private static function valid($resourceType)
    {
        return in_array($resourceType, static::$arrValidRecourseTypes);
    }

    /**
     * Call fieldset
     *
     * @param $resourceType
     * @param $arrModules
     *
     * @return array
     */
    private static function call($resourceType, $arrModules=null)
    {
        $controller = new OnOfficeRead();

        if(is_array(static::$arrParameter))
        {
            foreach (static::$arrParameter as $k=>$v)
            {
                $_GET[ $k ] = $v;
            }
        }

        if($arrModules !== null)
        {
            $_GET['modules'] = $arrModules;
        }

        $data = $controller->run($resourceType, null, null, array(), true);

        if(!$data['status']['errorcode'])
        {
            return $data['data'];
        }

        return null;
    }

    /**
     * Prevent direct instantiation (Singleton)
     */
    protected function __construct(){}

    /**
     * Prevent cloning of the object (Singleton)
     */
    final public function __clone(){}
}
