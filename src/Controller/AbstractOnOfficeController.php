<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller;

use onOffice\SDK\onOfficeSDK;

use Contao\Config;

/**
 * onOffice controller.
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
abstract class AbstractOnOfficeController
{
    protected $sdk = null;
    protected $token = null;
    protected $secret = null;
    protected $version = null;

    /**
     * Initialize the object
     */
    public function __construct($strToken = null, $strSecret = null, $strVersion = null)
    {
        // Set credentials
        $this->token = $strToken ?? Config::get('onOfficeApiToken');
        $this->secret = $strSecret ?? Config::get('onOfficeApiSecret');
        $this->version = $strVersion ?? (Config::get('onOfficeApiVersion') ?: 'stable');

        // Create SDK
        $this->sdk = new onOfficeSDK();
        $this->sdk->setApiVersion($this->version);
    }

    /**
     * Call the onOffice api
     */
    public function call(string $actionId, string $resourceType, array $parameters, ?string $resourceId=null, ?string $identifier=null): ?array
    {
        $handle = $this->sdk->call(
            $actionId,
            $resourceId,
            $identifier,
            $resourceType,
            $parameters
        );

        return $this->request($handle);
    }

    /**
     * Request the onOffice api
     */
    private function request(int $handleId): ?array
    {
        $this->sdk->sendRequests(
            $this->token,
            $this->secret
        );

        return $this->sdk->getResponseArray($handleId);
    }

    /**
     * Check if the response array has data
     */
    public function responseHasRecords(?array $arrResponse): bool
    {
        if(null === $arrResponse)
        {
            return false;
        }

        if(count(($arrResponse['data']['records'] ?? [])))
        {
            return true;
        }

        return false;
    }
}
