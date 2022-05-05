<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller\Options;

use onOffice\SDK\onOfficeSDK;
use Symfony\Component\Config\Definition\Exception\Exception;

final class ApiOptions extends Options
{
    protected function configure(): void
    {
        $this->set(self::MODE_READ, [
            'view',
            'addContactPerson',
            'contactPersonData',
            'savetemporary'
        ]);

        $this->set(self::MODE_CREATE, [
            'addOwner',
            'ownerData',
            'addBuyer',
            'buyerData',
            'addTenant',
            'tenantData',
            'addContactPerson',
            'contactPersonData'
        ]);
    }

    /**
     * Determines the type of the given contact relation and returns the valid ones
     */
    public function detectContactRelations(): ?array
    {
        $arrRelations = null;

        if(null === $this->validated)
        {
            throw new Exception('Use the validate method before executing detectContactRelation.');
        }

        if($this->isValid('addContactPerson') && $this->isValid('contactPersonData'))
        {
            $arrRelations[] = [
                $this->validated[$this->mode]['contactPersonData'],
                onOfficeSDK::RELATION_TYPE_CONTACT_PERSON
            ];
        }

        if($this->isValid('addOwner') && $this->isValid('ownerData'))
        {
            $arrRelations[] = [
                $this->validated[$this->mode]['ownerData'],
                onOfficeSDK::RELATION_TYPE_ESTATE_ADDRESS_OWNER
            ];
        }

        if($this->isValid('addBuyer') && $this->isValid('buyerData'))
        {
            $arrRelations[] = [
                $this->validated[$this->mode]['buyerData'],
                onOfficeSDK::RELATION_TYPE_BUYER
            ];
        }

        if($this->isValid('addTenant') && $this->isValid('tenantData'))
        {
            $arrRelations[] = [
                $this->validated[$this->mode]['tenantData'],
                onOfficeSDK::RELATION_TYPE_TENANT
            ];
        }

        if($this->isValid('addBroker') && $this->isValid('brokerData'))
        {
            $arrRelations[] = [
                $this->validated[$this->mode]['brokerData'],
                onOfficeSDK::RELATION_TYPE_CONTACT_BROKER
            ];
        }

        return $arrRelations;
    }
}
