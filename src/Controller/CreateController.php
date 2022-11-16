<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller;

use onOffice\SDK\onOfficeSDK;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\AddressOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\AgentsLogOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\ApiOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\CalendarOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\EstateOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\Options;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\RelationOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\TaskOptions;
use Oveleon\ContaoOnofficeApiBundle\OnOfficeConstants;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * onOffice create api controller.
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class CreateController extends AbstractOnOfficeController
{
    /**
     * Controller entry
     */
    public function run(string $module, ?array $arrDefaultParam=null, bool $asArray=false)
    {
        $apiOptions = new ApiOptions(Options::MODE_CREATE);

        // Cleanup of parameters for api options
        $apiParameter = $apiOptions->validate($arrDefaultParam, true);

        switch ($module)
        {
            case OnOfficeConstants::CREATE_ESTATE:
                $estateOptions = new EstateOptions(Options::MODE_CREATE);

                // Cleanup of parameters for estate options
                $estateParameter = $estateOptions->validate($arrDefaultParam, true);

                $realEstate = $this->call(
                    onOfficeSDK::ACTION_ID_CREATE,
                    onOfficeSDK::MODULE_ESTATE,
                    $estateParameter
                );

                // Check whether reference persons are to be created
                if($this->responseHasRecords($realEstate) && null !== ($arrRelations = $apiOptions->detectContactRelations()))
                {
                    foreach ($arrRelations as $arrRelation)
                    {
                        [$contactParameter, $contactRelation] = $arrRelation;

                        // If resource id does not exist, create new contact person
                        if (null === ($childId = $contactParameter['resourceid']))
                        {
                            // Create contact person
                            $contactPerson = $this->run(
                                OnOfficeConstants::CREATE_ADDRESS,
                                $contactParameter,
                                true
                            );

                            $childId = $contactPerson['data']['records'][0]['id'] ?? null;
                        }
                        else
                        {
                            $blnContactExists = true;
                        }

                        if ($this->responseHasRecords($contactPerson) || $blnContactExists)
                        {
                            // Create relation (estate <-> contact person)
                            $this->call(
                                onOfficeSDK::ACTION_ID_CREATE,
                                'relation',
                                (new RelationOptions(Options::MODE_CREATE))
                                    ->validate([
                                        'parentid' => [$realEstate['data']['records'][0]['id']],
                                        'childid' => [$childId],
                                        'relationtype' => $contactRelation
                                    ])
                            );
                        }
                    }
                }

                break;
            case OnOfficeConstants::CREATE_ADDRESS:
                $data = $this->call(
                    onOfficeSDK::ACTION_ID_CREATE,
                    'address',
                    (new AddressOptions(Options::MODE_CREATE))
                        ->validate($arrDefaultParam, true)
                );

                break;
            case OnOfficeConstants::CREATE_APPOINTMENT:
                $data = $this->call(
                    onOfficeSDK::ACTION_ID_CREATE,
                    'calendar',
                    (new CalendarOptions(Options::MODE_CREATE))
                        ->validate($arrDefaultParam, true)
                );
                break;
            case OnOfficeConstants::CREATE_TASK:
                $data = $this->call(
                    onOfficeSDK::ACTION_ID_CREATE,
                    'task',
                    (new TaskOptions(Options::MODE_CREATE))
                        ->validate($arrDefaultParam, true)
                );
                break;
            case OnOfficeConstants::CREATE_AGENTS_LOG:
                $data = $this->call(
                    onOfficeSDK::ACTION_ID_CREATE,
                    'agentslog',
                    (new AgentsLogOptions(Options::MODE_CREATE))
                        ->validate($arrDefaultParam, true)
                );
                break;
        }

        if ($asArray)
        {
            return $data ?? [];
        }

        return new JsonResponse($data ?? []);
    }
}
