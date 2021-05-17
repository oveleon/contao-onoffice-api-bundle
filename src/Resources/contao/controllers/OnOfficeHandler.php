<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use onOffice\SDK\onOfficeSDK;

use Contao\Config;
use Contao\Frontend;
use Contao\System;

/**
 * onOffice controller.
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
abstract class OnOfficeHandler extends Frontend
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

        // Load the user object before calling the parent constructor
        $this->import('FrontendUser', 'User');

        // Check whether a user is logged in
        $objTokenChecker = System::getContainer()->get('contao.security.token_checker');

        \define('BE_USER_LOGGED_IN', $objTokenChecker->hasBackendUser());
        \define('FE_USER_LOGGED_IN', $objTokenChecker->hasFrontendUser());

        parent::__construct();
    }

    /**
     * Call the onOffice api
     *
     * @param String     $actionId      onOffice api action
     * @param String     $resourceType  onOffice api resource type
     * @param array      $parameters    Array of onOffice api parameters
     * @param int        $resourceId    Optional onOffice api resource id
     * @param String     $identifier    Optional onOffice api identifier
     *
     * @return array
     */
    public function call(string $actionId, string $resourceType, array $parameters, $resourceId=null, $identifier=null): array
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
     *
     * @param int $handleId Handle id of an onOffice call
     *
     * @return array
     */
    private function request(int $handleId): array
    {
        $this->sdk->sendRequests(
            $this->token,
            $this->secret
        );

        return $this->sdk->getResponseArray($handleId);
    }
}
