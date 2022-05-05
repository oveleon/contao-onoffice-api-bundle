<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use Contao\Controller;

class View
{
    /**
     * Return parameters by view alias
     */
    public function getParametersByIdOrAlias($view, array $param=[]): array
    {
        if(null === ($objView = OnofficeApiViewModel::findByIdOrAlias($view)))
        {
            return [];
        }

        Controller::loadDataContainer('tl_onoffice_api_view');

        $skipFields = ['id', 'tstamp', 'type', 'title', 'alias', 'published', 'protected', 'groups', 'onOfficeShopTvView'];

        // Skip given parameters
        foreach ($param as $field => $value)
        {
            if (!in_array($field, $skipFields))
            {
                $skipFields[] = $field;
            }
        }

        foreach ($objView->row() as $field => $value)
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
                        continue 2;
                    }

                    switch ($field) {
                        case 'filter':
                            $arrFilter = unserialize($value);

                            foreach ($arrFilter as $index => $filter)
                            {
                                if ($filter['field'] !== '' && $filter['op'] !== '' && $filter['val'] !== '')
                                {
                                    $op = Filter::getFilterOperator($filter['op']);
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
}
