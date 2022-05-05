<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use Contao\StringUtil;
use Contao\System;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\AuthenticatorOptions;
use Symfony\Component\HttpFoundation\Request;

class Authenticator
{
    private ?string $key = null;

    private ?OnofficeAuthModel $model = null;

    public function __construct()
    {
        // Create auth options
        $authOptions = new AuthenticatorOptions(AuthenticatorOptions::MODE_AUTH_DATA);

        // Validate passed data
        $authParameter = $authOptions->validate([], true);

        // Check if a key exists
        if($authOptions->isValid('key'))
        {
            $this->key = $authParameter['key'];

            // Check if key exists and set model
            if(null !== ($model = OnofficeAuthModel::findOneByKey($this->key)))
            {
                $this->model = $model;
            }
        }
    }

    public function isGranted(): bool
    {
        if($this->key && $this->model)
        {
            if($this->model->restrictIp && $this->model->restrictHost)
            {
                return $this->checkIp() && $this->checkHost();
            }

            if($this->model->restrictIp)
            {
                return $this->checkIp();
            }

            if($this->model->restrictHost)
            {
                return $this->checkHost();
            }

            return true;
        }

        return false;
    }

    private function checkIp(): bool
    {
        // Get current request
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        // Get trusted proxies
        $trustedProxies = StringUtil::deserialize($this->model->allowedIps, true);

        // Set trusted headers
        $trustedHeaderSet =
            Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO;

        Request::setTrustedProxies($trustedProxies, $trustedHeaderSet);

        // Check if ip is trusted
        if(in_array($request->getClientIp(), $trustedProxies))
        {
            return true;
        }

        return false;
    }

    private function checkHost(): bool
    {
        // Get current request
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        // Get trusted hosts
        $trustedHosts = StringUtil::deserialize($this->model->allowedHosts, true);
        $trustedHosts = array_map(fn($host) => parse_url($host, PHP_URL_HOST), $trustedHosts);

        // Check host ip is trusted
        if(in_array($request->getHost(), $trustedHosts))
        {
            return true;
        }

        return false;
    }
}
