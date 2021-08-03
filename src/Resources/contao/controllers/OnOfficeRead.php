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
     * @param String  $module          Plural name of onOffice module
     * @param int     $id              Id of onOffice module or resource id
     * @param int     $view            Id of onOffice api view
     * @param array   $arrDefaultParam Default params
     *
     * @return JsonResponse|array
     */
    public function run(string $module, $id=null, $view=null, $arrDefaultParam=array())
    {
        if (array_key_exists('view', $_GET))
        {
            $arrDefaultParam = $this->getViewParameters($_GET['view'], $arrDefaultParam);
        }
        elseif (!is_null($view))
        {
            $arrDefaultParam = $this->getViewParameters($view, $arrDefaultParam);
        }

        if (array_key_exists('filterid', $_GET))
        {
            $arrDefaultParam['filterid'] = $_GET['filterid'];
        }

        $data = array();

        switch ($module)
        {
            case 'estates':
                $addContactPerson = false;

                if ($arrDefaultParam['addContactPerson'])
                {
                    $addContactPerson = true;
                    unset($arrDefaultParam['addContactPerson']);
                }

                $arrValidParam = array('data', 'filterid', 'filter', 'listlimit', 'listoffset', 'sortby', 'formatoutput', 'estatelanguage', 'outputlanguage');

                if (!array_key_exists('data', $arrDefaultParam))
                {
                    $arrDefaultParam['data'] = array('Id', 'kaufpreis', 'lage', 'objektnr_extern');
                }
                else
                {
                    array_unshift($arrDefaultParam['data'], 'Id');
                }

                if (!is_null($id))
                {
                    $arrDefaultParam['filter'] = array('Id' => [['op' => '=', 'val' => $id]]);
                }

                $param = $this->getParameters($arrValidParam, $arrDefaultParam);
                $this->setFilterIdByUser($param);

                $data = $this->call(onOfficeSDK::ACTION_ID_READ, onOfficeSDK::MODULE_ESTATE, $param);

                // Resolve contact persons and update data records
                if ($addContactPerson)
                {
                    $arrEstateIds = array();
                    $arrContactIds = array();

                    foreach ($data['data']['records'] as $record)
                    {
                        $arrEstateIds[] = $record['id'];
                    }

                    $contactPersons = $this->call(onOfficeSDK::ACTION_ID_GET, 'idsfromrelation', array('relationtype'=>onOfficeSDK::RELATION_TYPE_CONTACT_BROKER, 'parentids'=>$arrEstateIds));

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

                    $addresses = $this->call(onOfficeSDK::ACTION_ID_READ, onOfficeSDK::MODULE_ADDRESS, array('recordids'=>$arrContactIds, 'data'=>['Anrede','Email','Name','Vorname','Land','Ort','Plz','Strasse','Telefon1','imageUrl','Kundenlogo','Zusatz1']));
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
            case 'estatepictures':
                $arrValidParam = array('estateids', 'categories', 'size', 'language');

                if (!array_key_exists('categories', $arrDefaultParam))
                {
                    $arrDefaultParam['categories'] = array('Titelbild', 'Foto', 'Foto_gross', 'Grundriss', 'Lageplan', 'Epass_Skala', 'Panorama');
                }

                if (is_array($id))
                {
                    $arrDefaultParam['estateids'] = $id;
                }
                elseif (!is_null($id))
                {
                    $arrDefaultParam['estateids'] = array($id);
                }

                $param = $this->getParameters($arrValidParam, $arrDefaultParam);

                $data = $this->call(onOfficeSDK::ACTION_ID_GET, 'estatepictures', $param);

                // Store images temporary
                if (array_key_exists('savetemporary', $_GET) && $_GET['savetemporary'])
                {
                    $this->cacheEstatePictures($data);
                }
                break;
            case 'addresses':
                $arrValidParam = array('data', 'recordids', 'filterid', 'listlimit', 'listoffset', 'formatoutput', 'outputlanguage');

                if (!array_key_exists('data', $arrDefaultParam))
                {
                    $arrDefaultParam['data'] = array('Briefanrede', 'Email', 'Land', 'Name', 'Ort', 'Plz', 'Strasse', 'Vorname', 'AGB_akzeptiert', 'imageUrl');
                }

                if (!is_null($id))
                {
                    $arrDefaultParam['recordids'] = array($id);
                }

                $param = $this->getParameters($arrValidParam, $arrDefaultParam);
                $this->setFilterIdByUser($param);

                $data = $this->call(onOfficeSDK::ACTION_ID_READ, onOfficeSDK::MODULE_ADDRESS, $param);
                break;
            case 'agentslogs':
                $arrValidParam = array('data', 'tracking', 'estateid', 'listlimit');

                if (!array_key_exists('data', $arrDefaultParam))
                {
                    $arrDefaultParam['data'] = array('Objekt_nr', 'Aktionsart', 'Aktionstyp', 'Datum', 'Bemerkung', 'merkmal');
                }

                if (!is_null($id))
                {
                    $arrDefaultParam['estateid'] = $id;
                }

                $param = $this->getParameters($arrValidParam, $arrDefaultParam);

                $data = $this->call(onOfficeSDK::ACTION_ID_READ, 'agentslog', $param);
                break;
            case 'users':
                // ToDo
                $arrValidParam = array('data', 'filter', 'sortby', 'listlimit');

                if (!array_key_exists('data', $arrDefaultParam))
                {
                    $arrDefaultParam['data'] = array('Nachname');
                }

                $param = $this->getParameters($arrValidParam, $arrDefaultParam);

                $data = $this->call(onOfficeSDK::ACTION_ID_READ, 'user', $param);
                break;
            case 'fields':
                $arrValidParam = array('labels', 'language', 'fieldList', 'modules', 'showOnlyInactive');

                if (!is_null($id))
                {
                    $arrDefaultParam['modules'] = array($id);
                }

                $param = $this->getParameters($arrValidParam, $arrDefaultParam);

                $data = $this->call(onOfficeSDK::ACTION_ID_GET, 'fields', $param);
                break;
            case 'searchcriterias':
                $arrValidParam = array('mode', 'ids');

                $param = $this->getParameters($arrValidParam, $arrDefaultParam);

                $data = $this->call(onOfficeSDK::ACTION_ID_GET, 'searchcriterias', $param);
                break;
            case 'searchcriteriafields':
                $data = $this->call(onOfficeSDK::ACTION_ID_GET, 'searchCriteriaFields', array());
                break;
            case 'qualifiedsuitors':
                $arrValidParam = array('estatedata');

                $param = $this->getParameters($arrValidParam, $arrDefaultParam);

                $data = $this->call(onOfficeSDK::ACTION_ID_GET, 'qualifiedsuitors', $param);

                break;
            case 'regions':
                $arrValidParam = array('language');

                $param = $this->getParameters($arrValidParam, $arrDefaultParam);

                $data = $this->call(onOfficeSDK::ACTION_ID_GET, 'regions', $param);
                break;
            case 'search':
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
                        $arrValidParam = array('includecontactdata', 'casesensitive', 'searchparameter', 'listlimit', 'extendedclaim', 'input', 'extendedclaim');
                        break;
                    case 'searchcriteria':
                        $arrValidParam = array('searchdata', 'outputall', 'outputfields', 'groupbyaddress', 'offset', 'limit', 'order');
                        break;
                    default:
                        $arrValidParam = array('input', 'extendedclaim');
                }

                $param = $this->getParameters($arrValidParam, $arrDefaultParam);

                $data = $this->call(onOfficeSDK::ACTION_ID_GET, 'search', $param, $id);
                break;
        }

        return array('data' => $data['data'], 'status' => $data['status']);
    }

    /**
     * Return parameters by GET method
     *
     * @param array $arrValidParam  Array of valid parameters
     * @param array $param          Optional array of default parameters
     *
     * @return array
     */
    private function getParameters($arrValidParam, $param=array())
    {
        foreach ($_GET as $key => $value)
        {
            if ($key === 'data' && !is_array($value))
            {
                $value = explode(',', $value);
            }

            if (in_array($key, $arrValidParam))
            {
                $param[$key] = $value;
            }
        }

        return $param;
    }

    /**
     * Set filterid by an user
     *
     * @param array $param Array of default parameters
     */
    private function setFilterIdByUser(&$param)
    {
        if ($param['filterid'] !== 0 && $param['filterid'] !== null)
        {
            return;
        }

        if ($_SESSION['onOfficeBranchGroup'])
        {
            $objMemberGroup = MemberGroupModel::findByPk($_SESSION['onOfficeBranchGroup']);
            unset($_SESSION['onOfficeBranchGroup']);
        }
        else
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
    private function getViewParameters($view, $param=array())
    {
        if (is_int($view))
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
     *
     * @param String $operator  Operator alias
     *
     * @return array
     */
    private function getOperator($operator)
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
