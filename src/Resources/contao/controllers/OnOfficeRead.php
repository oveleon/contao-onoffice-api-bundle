<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use onOffice\SDK\onOfficeSDK;
use Contao\MemberGroupModel;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * onOffice read api controller.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class OnOfficeRead extends OnOfficeHandler
{
    /**
     * Run the controller
     *
     * @param String  $module           Plural name of onOffice module
     * @param int|null $id              Id of onOffice module or resource id
     * @param int|null $view            Id of onOffice api view
     * @param array $arrDefaultParam    Default params
     * @param boolean $asArray          Return as array flag
     *
     * @return JsonResponse|array
     */
    public function run(string $module, ?int $id=null, ?int $view=null, array $arrDefaultParam=[], bool $asArray=false)
    {
        $apiOptions = new ApiOptions(Options::MODE_READ);

        // Cleanup of parameters for API Options
        $arrDefaultParam = $apiOptions->validate($arrDefaultParam, true);

        // Extend view parameters
        if ($apiOptions->isValid('view'))
        {
            $arrDefaultParam = $this->getViewParameters($arrDefaultParam['view'], $arrDefaultParam);
        }
        elseif (null !== $view)
        {
            $arrDefaultParam = $this->getViewParameters($view, $arrDefaultParam);
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

                // ToDo: ?
                $this->setFilterIdByUser($estateParameter);

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

                // ToDo: use EstatePictureOptions
                $estatePictureOptions = new EstatePictureOptions(Options::MODE_READ);

                // Cleanup of parameters for Estate Options
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
                if ($apiOptions->isValid('savetemporary'))
                {
                    $this->cacheEstatePictures($data);
                }
                break;
            case OnOfficeConstants::READ_ADDRESSES:

                $addressOptions = new AddressOptions(Options::MODE_READ);

                // Cleanup of parameters for Estate Options
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

                // ToDo: ?
                $this->setFilterIdByUser($addressParameter);

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_READ,
                    onOfficeSDK::MODULE_ADDRESS,
                    $addressParameter
                );
                break;
            case OnOfficeConstants::READ_AGENTS_LOGS:
                $agentsLogOptions = new AgentsLogOptions(Options::MODE_READ);

                // Cleanup of parameters for Estate Options
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

                // Cleanup of parameters for Estate Options
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

                // Cleanup of parameters for Estate Options
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

                // Cleanup of parameters for Estate Options
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

                // Cleanup of parameters for Estate Options
                $qualifiedSuitorParameter = $qualifiedSuitorOptions->validate($arrDefaultParam, true);

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_GET,
                    'qualifiedsuitors',
                    $qualifiedSuitorParameter
                );
                break;
            case OnOfficeConstants::READ_REGIONS:
                $regionOptions = new QualifiedSuitorOptions(Options::MODE_READ);

                // Cleanup of parameters for Estate Options
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

                // Cleanup of parameters for Estate Options
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

    /**
     * Set filter-id by user
     */
    private function setFilterIdByUser(array &$param)
    {
        $objMemberGroup = null;

        if (array_key_exists('filterid', $param) && $param['filterid'] !== 0 && $param['filterid'] !== null)
        {
            return;
        }

        if (array_key_exists('onOfficeBranchGroup', $_SESSION))
        {
            $objMemberGroup = MemberGroupModel::findByPk($_SESSION['onOfficeBranchGroup']);
            unset($_SESSION['onOfficeBranchGroup']);
        }
        elseif($this->User->onOfficeBranchGroup)
        {
            $objMemberGroup = MemberGroupModel::findByPk($this->User->onOfficeBranchGroup);
        }

        if ($objMemberGroup === null || $objMemberGroup->filterid === 0)
        {
            return;
        }

        $param['filterid'] = $objMemberGroup->filterid;
    }

    /**
     * Return parameters by view alias from database
     *
     * @param string $view   Alias of the view
     * @param array  $param  Default parameters
     *
     * @return array
     */
    private function getViewParameters($view, $param=[])
    {
        if (is_numeric($view))
        {
            $objApiView = $this->Database->prepare("SELECT * FROM tl_onoffice_api_view WHERE id=? AND published=1")
                ->limit(1)
                ->execute($view);
        }
        else
        {
            $objApiView = $this->Database->prepare("SELECT * FROM tl_onoffice_api_view WHERE alias=? AND published=1")
                ->limit(1)
                ->execute($view);
        }

        if ($objApiView->numRows === 0)
        {
            return array();
        }

        $this->loadDataContainer('tl_onoffice_api_view');

        $skipFields = array('id', 'tstamp', 'type', 'title', 'alias', 'published', 'protected', 'groups', 'onOfficeShopTvView');

        // Skip given parameters
        foreach ($param as $field => $value)
        {
            if (!in_array($field, $skipFields))
            {
                $skipFields[] = $field;
            }
        }

        $arrApiView = $objApiView->fetchAssoc();

        foreach ($arrApiView as $field => $value)
        {
            if (in_array($field, $skipFields))
            {
                continue;
            }

            switch ($GLOBALS['TL_DCA']['tl_onoffice_api_view']['fields'][$field]['inputType'])
            {
                case 'text':
                    if ($value)
                    {
                        $param[$field] = $value;
                    }
                    break;
                case 'select':
                    if ($value !== '')
                    {
                        $param[$field] = $value;
                    }
                    break;
                case 'checkbox':
                    if ($value !== '')
                    {
                        $param[$field] = true;
                    }
                    break;
                case 'checkboxWizard':
                    if (!is_null($value))
                    {
                        $param[$field] = unserialize($value);
                    }
                    break;
                case 'multiColumnWizard':
                    if (is_null($value))
                    {
                        continue;
                    }

                    switch ($field) {
                        case 'filter':
                            $arrFilter = unserialize($value);

                            foreach ($arrFilter as $index => $filter)
                            {
                                if ($filter['field'] !== '' && $filter['op'] !== '' && $filter['val'] !== '')
                                {
                                    $op = $this->getOperator($filter['op']);
                                    $val = $filter['val'];

                                    if ($filter['op'] === 'in' || $filter['op'] === 'not_in')
                                    {
                                        $val = explode(',', $val);
                                    }

                                    $param[$field][$filter['field']][$index]['op']  = $op;
                                    $param[$field][$filter['field']][$index]['val'] = $val;
                                }
                            }
                            break;
                        case 'sortby':
                            $arrSortBy = unserialize($value);

                            foreach ($arrSortBy as $item)
                            {
                                if ($item['field'] !== '' && $item['sorting'] !== '')
                                {
                                    $param[$field][$item['field']] = $item['sorting'];
                                }
                            }
                            break;
                        case 'recordids':
                        case 'estateids':
                            $arrIds = unserialize($value);

                            foreach ($arrIds as $item)
                            {
                                if ($item['id'] !== '')
                                {
                                    $param[$field][] = $item['id'];
                                }
                            }
                            break;
                    }
                    break;
            }
        }

        return $param;
    }

    /**
     * Cache estate pictures on the file system
     *
     * @param array $data  Response data of file records
     */
    private function cacheEstatePictures(&$data)
    {
        if (!is_array($data['data']['records']))
        {
            return;
        }

        foreach ($data['data']['records'] as &$record)
        {
            $fileName = $record['elements'][0]['originalname'];
            $projectDir = \System::getContainer()->getParameter('kernel.project_dir');
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

    /**
     * Return actual operator by an operator alias
     */
    private function getOperator(string $operator): string
    {
        $mapper = array
        (
            'equal' => '=',
            'greater' => '>',
            'less' => '<',
            'greater_equal' => '>=',
            'less_equal' => '<=',
            'not_equal' => '!=',
            'compare' => '<>',
            'between' => 'between',
            'like' => 'like',
            'not_like' => 'not like',
            'in' => 'in',
            'not_in' => 'not in',
        );

        return $mapper[$operator];
    }
}
