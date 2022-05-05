<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use Oveleon\ContaoOnofficeApiBundle\Controller\ReadController;

/**
 * Fieldset class
 *
 * @todo as Service Container
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class Fieldset
{
    /**
     * Output format constants (get)
     */
    const FORMAT_ORIGINAL = 0;
    const FORMAT_FLAT = 1;
    const FORMAT_KEYS = 2;

    /**
     * Object instance (Singleton)
     */
    protected static Fieldset $objInstance;

    /**
     * The fieldset data
     * @var array
     */
    protected static array $arrData = [];

    /**
     * Valid resource types
     */
    protected static array $arrValidRecourseTypes = [
        'fields',
        'searchcriteriafields'
    ];

    /**
     * Show Labels
     */
    public static array $arrParameter = [
        'labels' => true
    ];

    /**
     * Instantiate the Fieldset object
     */
    public static function getInstance(): Fieldset
    {
        if (static::$objInstance === null)
        {
            static::$objInstance = new static();
        }

        return static::$objInstance;
    }

    /**
     * Check whether a key is set
     */
    public static function has(string $resourceType, string $module=''): bool
    {
        if($module)
        {
            return isset(static::$arrData[$resourceType][$module]);
        }

        return isset(static::$arrData[$resourceType]);
    }

    /**
     * Return a module entry
     */
    public static function get(string $resourceType, $arrModules = null, int $format = self::FORMAT_ORIGINAL)
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
            // call and set non-existing modules
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

        return static::format($data, $format);
    }

    /**
     * Format data array
     */
    public static function format(?array $data, int $format): ?array
    {
        if(null === $data)
        {
            return null;
        }

        $arrFormatted = [];

        switch ($format)
        {
            case self::FORMAT_FLAT:

                foreach ($data as $module => $d)
                {
                    $arrFormatted[ $module ] = $d['elements'] ?? [];

                    // Remove label
                    if(array_key_exists('label', $arrFormatted[ $module ]))
                    {
                        unset($arrFormatted[ $module ]['label']);
                    }
                }
                break;

            case self::FORMAT_KEYS:

                foreach ($data as $module => $d)
                {
                    $arrFormatted[ $module ] = array_keys($d['elements'] ?? []);

                    // Remove label
                    if(($index = array_search('label', $arrFormatted[ $module ])) !== false)
                    {
                        array_splice($arrFormatted[ $module ], $index, 1);
                    }
                }
                break;

            default:
                return $data;
        }

        return $arrFormatted;
    }

    /**
     * Add and restructure a fieldset entry from api response
     */
    public static function set(string $resourceType, $varValue)
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
    public static function remove(string $resourceType, string $module='')
    {
        if($module)
        {
            unset(static::$arrData[$resourceType][$module]);
        }

        unset(static::$arrData[$resourceType]);
    }

    /**
     * Check is resourceType valid
     */
    private static function valid(string $resourceType): bool
    {
        return in_array($resourceType, static::$arrValidRecourseTypes);
    }

    /**
     * Call fieldset
     */
    private static function call(string $resourceType, $arrModules=null): ?array
    {
        $controller = new ReadController();
        $parameters = [];

        if(is_array(static::$arrParameter))
        {
            $parameters = static::$arrParameter;
        }

        if($arrModules !== null)
        {
            $parameters['modules'] = $arrModules;
        }

        $data = $controller->run(
            $resourceType,
            null,
            null,
            $parameters,
            true
        );

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
