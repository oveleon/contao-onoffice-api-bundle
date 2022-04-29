<?php
/**
 * Options:
 * This class is used to pass only valid parameters to the onOffice API to avoid errors.
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */

namespace Oveleon\ContaoOnofficeApiBundle;

use Contao\System;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Usage:
 *  $objOptions = new MyOptions(Options::MODE_READ);
 *
 *  $validReadParameter = $objOptions->get();
 *  $validReadOptions = $objOptions->validate(['kaufpreis']);
 *  $validCreateOptions = $objOptions->validate(['kaufpreis'], true);
 *
 *  $objOptions->setMode(Options::MODE_CREATE);
 *  $validCreateParameter = $objOptions->get();
 */
abstract class Options implements OptionsInterface
{
    const MODE_READ = 0;
    const MODE_EDIT = 1;
    const MODE_CREATE = 2;
    const MODE_UPLOAD = 3;

    /**
     * Module name
     * Used to retrieve field configurations from onOffice.
     */
    protected ?string $name;

    /**
     * Accepted modes
     * Defines the modes which are extended by the field configurations.
     */
    protected ?array $modes;

    /**
     * Current mode scope
     */
    protected int $mode;

    /**
     * Validated data
     */
    private ?array $validated = null;

    /**
     * Parameter bag
     */
    protected array $parameters = [];

    public function __construct($mode)
    {
        $this->setName();
        $this->setModes();
        $this->configure();

        // Set mode scope
        $this->setMode($mode);

        // Load valid module fields (data node)
        if(null !== $this->name && null !== $this->modes)
        {
            $fieldset = Fieldset::get('fields', [$this->name], Fieldset::FORMAT_KEYS);

            // Pass 'data' node to valid modes
            foreach ($this->modes as $m) {
                $this->set($m, ['data' => $fieldset[$this->name]]);
            }
        }
    }

    /**
     * Set module name
     */
    abstract protected function setName(): void;

    /**
     * Set accepted modes
     */
    abstract protected function setModes(): void;

    /**
     * Set accepted modes
     */
    abstract protected function configure(): void;

    /**
     * Set valid parameter additive by mode
     */
    protected function set(int $mode, array $data): void
    {
        $this->parameters[ $mode ] = array_merge_recursive($this->parameters[ $mode ] ?? [], $data);
    }

    /**
     * Reset all options by mode
     */
    protected function reset(int $mode): void
    {
        if(array_key_exists($mode, $this->parameters))
        {
            unset($this->parameters[ $mode ]);
        }
    }

    /**
     * Set current mode
     */
    public function setMode(int $mode): void
    {
        $this->mode = $mode;
    }

    /**
     * Return all options by current mode
     */
    public function get(): array
    {
        return $this->parameters[ $this->mode ] ?? [];
    }

    /**
     * Check if a key exists for validated data
     */
    public function isValid($key): bool
    {
        if(null === $this->validated)
        {
            throw new Exception('Before isValid method can be executed, the validate method must be executed.');
        }

        if(!array_key_exists($this->mode, $this->validated))
        {
            throw new Exception('No validation has been performed yet for the mode used.');
        }

        if(array_key_exists($key, $this->validated[ $this->mode ]))
        {
            return true;
        }

        return false;
    }

    /**
     * Validate and return valid options
     */
    public function validate(array $param, bool $includeRequestParameter = false): array
    {
        $accepts = [];

        if($includeRequestParameter)
        {
            $request = System::getContainer()->get('request_stack')->getCurrentRequest();

            if($request->query->count())
            {
                foreach ($request->query->all() as $key => $value)
                {
                    if(is_array($value) && array_key_exists($key, $param))
                    {
                        foreach ($value as $v)
                        {
                            if(!in_array($v, $param[ $key ]))
                            {
                                $param[ $key ][] = $v;
                            }
                        }
                    }
                    elseif(!array_key_exists($key, $param))
                    {
                        $param[ $key ] = $value;
                    }
                }
            }
        }

        foreach (self::get() as $key => $val)
        {
            if(is_array($val) && array_key_exists($key, $param))
            {
                $accepts[ $key ] = [];

                foreach ($val as $v)
                {
                    switch ($this->mode)
                    {
                        // list of valid key-value pairs
                        case self::MODE_CREATE:
                        case self::MODE_EDIT:
                        case self::MODE_UPLOAD:
                            if(array_key_exists($v, $param[ $key ]))
                            {
                                $accepts[ $key ][ $v ] = $param[ $key ][ $v ];
                            }

                            break;

                        // list of valid keys
                        default:
                            if(in_array($v, $param[ $key ]))
                            {
                                $accepts[ $key ][] = $v;
                            }
                    }
                }

                continue;
            }

            if(array_key_exists($val, $param))
            {
                $accepts[ $val ] = $param[ $val ];
            }
        }

        $this->validated[ $this->mode ] = $accepts;

        return $accepts;
    }
}
