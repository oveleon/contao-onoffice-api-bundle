<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller;

use Contao\System;
use onOffice\SDK\onOfficeSDK;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\AddressOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\AgentsLogOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\ApiOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\EstateOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\EstatePictureOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\FieldOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\Options;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\QualifiedSuitorOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\RegionOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\SearchAddressOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\SearchCriteriaOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\SearchEstateOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\SearchSearchCriteriaOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\UserOptions;
use Oveleon\ContaoOnofficeApiBundle\Filter;
use Oveleon\ContaoOnofficeApiBundle\OnOfficeConstants;
use Oveleon\ContaoOnofficeApiBundle\View;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * onOffice read api controller.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class ReadController extends AbstractOnOfficeController
{
    /**
     * Controller entry
     */
    public function run(string $module, $id=null, ?int $view=null, array $arrDefaultParam=[], bool $asArray=false)
    {
        $apiOptions = new ApiOptions(Options::MODE_READ);

        // Cleanup of parameters for API Options
        $apiParameter = $apiOptions->validate($arrDefaultParam, true);

        // Extend view parameters
        if ($apiOptions->isValid('view') || null !== $view)
        {
            $arrDefaultParam = (new View())
                ->getParametersByIdOrAlias(
                    $apiParameter['view'] ?? $view,
                    $arrDefaultParam
                );
        }

        switch ($module)
        {
            case OnOfficeConstants::READ_ESTATES:

                // Check if a single property is called
                if (null !== $id)
                {
                    // Create filters for retrieving a single property
                    $arrDefaultParam['filter'] = [
                        'Id' => [ ['op' => '=', 'val' => $id] ]
                    ];
                }

                $estateOptions = new EstateOptions(Options::MODE_READ);

                // Cleanup of parameters for Estate Options
                $estateParameter = $estateOptions->validate($arrDefaultParam, true);

                // Set default values if no data is passed
                if(!$estateOptions->isValid('data'))
                {
                    $estateParameter['data'] = ['Id'];
                }

                Filter::setFilterIdByUser($estateParameter);

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_READ,
                    onOfficeSDK::MODULE_ESTATE,
                    $estateParameter
                );

                // Resolve contact persons and extend data records
                if ($apiOptions->isValid('addContactPerson'))
                {
                    $arrEstateIds = [];
                    $arrContactIds = [];

                    foreach ($data['data']['records'] as $record)
                    {
                        $arrEstateIds[] = $record['id'];
                    }

                    $contactPersons = $this->call(
                        onOfficeSDK::ACTION_ID_GET,
                        'idsfromrelation',
                        [
                            'relationtype' => onOfficeSDK::RELATION_TYPE_CONTACT_BROKER,
                            'parentids' => $arrEstateIds
                        ]
                    );

                    foreach ($contactPersons['data']['records'][0]['elements'] as $id => $contactIds)
                    {
                        foreach ($contactIds as $contactId)
                        {
                            if (!in_array($contactId, $arrContactIds))
                            {
                                $arrContactIds[] = $contactId;
                            }
                        }
                    }

                    $addressParameter = (new AddressOptions(Options::MODE_READ))->validate([
                        'recordids' => $arrContactIds,
                        'data'      => $apiOptions->isValid('contactPersonData') ? $arrDefaultParam['contactPersonData'] : ['KdNr']
                    ]);

                    $addresses = $this->call(
                        onOfficeSDK::ACTION_ID_READ,
                        onOfficeSDK::MODULE_ADDRESS,
                        $addressParameter
                    );

                    $arrAddresses = array();

                    foreach ($addresses['data']['records'] as $address)
                    {
                        $arrAddresses[$address['id']] = $address['elements'];
                    }

                    foreach ($data['data']['records'] as &$record)
                    {
                        foreach ($contactPersons['data']['records'][0]['elements'][$record['id']] as $contactId)
                        {
                            $record['elements']['ansprechpartner'][] = $arrAddresses[$contactId];
                        }
                    }
                }
                break;
            case OnOfficeConstants::READ_ESTATE_PICTURES:

                $estatePictureOptions = new EstatePictureOptions(Options::MODE_READ);

                // Cleanup of parameters for estate pictures Options
                $estatePictureParameter = $estatePictureOptions->validate($arrDefaultParam, true);

                // Set default values if no data is passed
                if(!$estatePictureOptions->isValid('categories'))
                {
                    $estateParameter['categories'] = ['Titelbild'];
                }

                if (is_array($id))
                {
                    $arrDefaultParam['estateids'] = $id;
                }
                elseif (null !== $id)
                {
                    $arrDefaultParam['estateids'] = [$id];
                }

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_GET,
                    'estatepictures',
                    $estatePictureParameter
                );

                // Store images temporary
                if ($apiOptions->isValid('savetemporary') && $this->responseHasRecords($data))
                {
                    // Cache estate pictures
                    foreach ($data['data']['records'] as &$record)
                    {
                        // ToDo: Rewrite (e.g. new Contao\File())
                        $fileName = $record['elements'][0]['originalname'];
                        $projectDir = System::getContainer()->getParameter('kernel.project_dir');
                        $targetDir = '/assets/estatepictures';
                        $fullTargetDir = $projectDir . $targetDir;
                        $fullPath = $fullTargetDir . '/' . $fileName;
                        $filePath = $targetDir . '/' . $fileName;

                        // Create folder if not exist
                        if (!is_dir($fullTargetDir))
                        {
                            mkdir($fullTargetDir);
                        }

                        file_put_contents($fullPath, file_get_contents($record['elements'][0]['url']));

                        $record['elements'][0]['temporarypicture']['path'] = $filePath;

                        $imageSize = getimagesize($fullPath);

                        if (is_array($imageSize))
                        {
                            $record['elements'][0]['temporarypicture']['width'] = $imageSize[0];
                            $record['elements'][0]['temporarypicture']['height'] = $imageSize[1];
                        }
                    }

                }
                break;
            case OnOfficeConstants::READ_ADDRESSES:

                $addressOptions = new AddressOptions(Options::MODE_READ);

                // Cleanup of parameters for Address Options
                $addressParameter = $addressOptions->validate($arrDefaultParam, true);

                // Set default values if no data is passed
                if(!$addressOptions->isValid('data'))
                {
                    $addressParameter['data'] = ['KdNr'];
                }

                // Check if a single property is called
                if (null !== $id)
                {
                    $addressParameter['recordids'] = [$id];
                }

                Filter::setFilterIdByUser($addressParameter);

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_READ,
                    onOfficeSDK::MODULE_ADDRESS,
                    $addressParameter
                );
                break;
            case OnOfficeConstants::READ_AGENTS_LOGS:
                $agentsLogOptions = new AgentsLogOptions(Options::MODE_READ);

                // Cleanup of parameters for agent logs Options
                $agentsLogParameter = $agentsLogOptions->validate($arrDefaultParam, true);

                // Set default values if no data is passed
                if(!$agentsLogOptions->isValid('data'))
                {
                    $agentsLogParameter['data'] = ['Aktionsart'];
                }

                // Check if a single property is called
                if (null !== $id)
                {
                    $agentsLogParameter['estateid'] = $id;
                }

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_READ,
                    'agentslog',
                    $agentsLogParameter
                );
                break;
            case OnOfficeConstants::READ_USERS:
                $userOptions = new UserOptions(Options::MODE_READ);

                // Cleanup of parameters for user Options
                $userParameter = $userOptions->validate($arrDefaultParam, true);

                // Set default values if no data is passed
                if(!$userOptions->isValid('data'))
                {
                    $userParameter['data'] = ['Nachname'];
                }

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_READ,
                    'user',
                    $userParameter
                );
                break;
            case OnOfficeConstants::READ_FIELDS:
                $fieldOptions = new FieldOptions(Options::MODE_READ);

                // Cleanup of parameters for field Options
                $fieldParameter = $fieldOptions->validate($arrDefaultParam, true);

                // Check if a single property is called
                if (null !== $id)
                {
                    $fieldParameter['modules'] = [$id];
                }

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_GET,
                    'fields',
                    $fieldParameter
                );
                break;
            case OnOfficeConstants::READ_SEARCH_CRITERIAS:
                $searchCriteriaOptions = new SearchCriteriaOptions(Options::MODE_READ);

                // Cleanup of parameters for search criteria Options
                $searchCriteriaParameter = $searchCriteriaOptions->validate($arrDefaultParam, true);

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_GET,
                    'searchcriterias',
                    $searchCriteriaParameter
                );
                break;
            case OnOfficeConstants::READ_SEARCH_CRITERIA_FIELDS:
                $data = $this->call(
                    onOfficeSDK::ACTION_ID_GET,
                    'searchCriteriaFields',
                    []
                );
                break;
            case OnOfficeConstants::READ_QUALIFIED_SUITORS:
                $qualifiedSuitorOptions = new QualifiedSuitorOptions(Options::MODE_READ);

                // Cleanup of parameters for qualified suitors Options
                $qualifiedSuitorParameter = $qualifiedSuitorOptions->validate($arrDefaultParam, true);

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_GET,
                    'qualifiedsuitors',
                    $qualifiedSuitorParameter
                );
                break;
            case OnOfficeConstants::READ_REGIONS:
                $regionOptions = new RegionOptions(Options::MODE_READ);

                // Cleanup of parameters for region Options
                $regionParameter = $regionOptions->validate($arrDefaultParam, true);

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_GET,
                    'regions',
                    $regionParameter
                );
                break;
            case OnOfficeConstants::READ_SEARCH:

                if(!$id)
                {
                    $data['status'] = array(
                        "errorcode" => "WRONG_PARAMETER",
                        "message"   => "No Resource ID could be found. Possible Resource IDs: address, estate, searchcriteria"
                    );
                    break;
                }

                switch($id)
                {
                    case 'address':
                        $searchOptions = new SearchAddressOptions(Options::MODE_READ);
                        break;
                    case 'searchcriteria':
                        $searchOptions = new SearchSearchCriteriaOptions(Options::MODE_READ);
                        break;
                    default:
                        $searchOptions = new SearchEstateOptions(Options::MODE_READ);
                }

                // Cleanup of parameters for search Options
                $searchParameter = $searchOptions->validate($arrDefaultParam, true);

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_GET,
                    'search',
                    $searchParameter,
                    $id
                );
                break;
        }

        $response = [
            'data' => $data['data'] ?? null,
            'status' => $data['status'] ?? null
        ];

        if ($asArray)
        {
            return $response;
        }

        return new JsonResponse($response);
    }
}
