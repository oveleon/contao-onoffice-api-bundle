<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use Contao\FrontendUser;
use Contao\MemberGroupModel;
use Contao\System;

class Filter
{
    /**
     * Set filter-id by user
     */
    public static function setFilterIdByUser(array &$param): void
    {
        if(
            null === ($user = System::importStatic('FrontendUser')) ||
            (
                array_key_exists('filterid', $param) &&
                !!$param['filterid']
            )
        )
        {
            return;
        }

        $objMemberGroup = null;
        $objSession = System::getContainer()->get('session');

        if ($objSession->has('onOfficeBranchGroup'))
        {
            $objMemberGroup = MemberGroupModel::findByPk($objSession->get('onOfficeBranchGroup'));

            $objSession->remove('onOfficeBranchGroup');
        }
        elseif($user->onOfficeBranchGroup)
        {
            $objMemberGroup = MemberGroupModel::findByPk($user->onOfficeBranchGroup);
        }

        if ($objMemberGroup === null || $objMemberGroup->filterid === 0)
        {
            return;
        }

        $param['filterid'] = $objMemberGroup->filterid;
    }

    /**
     * Return actual operator by an operator alias
     */
    public static function getFilterOperator(string $operator): string
    {
        $mapper = [
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
            'not_in' => 'not in'
        ];

        return $mapper[$operator];
    }
}
