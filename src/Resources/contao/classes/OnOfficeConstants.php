<?php

namespace Oveleon\ContaoOnofficeApiBundle;

class OnOfficeConstants
{
    const READ_ESTATES = 'estates';
    const READ_ESTATE_PICTURES = 'estatepictures';
    const READ_ADDRESSES = 'addresses';
    const READ_AGENTS_LOGS = 'agentslogs';
    const READ_USERS = 'users';
    const READ_FIELDS = 'fields';
    const READ_SEARCH_CRITERIAS = 'searchcriterias';
    const READ_SEARCH_CRITERIA_FIELDS = 'searchcriteriafields';
    const READ_QUALIFIED_SUITORS = 'qualifiedsuitors';
    const READ_REGIONS = 'regions';
    const READ_SEARCH = 'search';

    const CREATE_ESTATE = 'estate';
    const CREATE_APPOINTMENT = 'appointment';
    const CREATE_TASK = 'task';
    const CREATE_AGENTS_LOG = 'agentslog';
    const CREATE_ADDRESS = 'address';

    const ESTATE_STATUS_ACTIVE = 1;
    const ESTATE_STATUS_INACTIVE = 2;
    const ESTATE_STATUS_ARCHIVE = 0;
}
