<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller;

use onOffice\SDK\onOfficeSDK;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\AddressOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\EstateOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\FileOptions;
use Oveleon\ContaoOnofficeApiBundle\Controller\Options\Options;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * onOffice edit api controller.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class EditController extends AbstractOnOfficeController
{
    /**
     * Controller entry
     */
    public function run(string $module, $id, array $arrDefaultParam=[], bool $asArray=false)
    {
        switch ($module)
        {
            case 'estates':
                $data = $this->call(
                    onOfficeSDK::ACTION_ID_MODIFY,
                    onOfficeSDK::MODULE_ESTATE,
                    (new EstateOptions(Options::MODE_EDIT))
                        ->validate($arrDefaultParam, true),
                    $id
                );
                break;
            case 'addresses':
                $data = $this->call(
                    onOfficeSDK::ACTION_ID_MODIFY,
                    onOfficeSDK::MODULE_ADDRESS,
                    (new AddressOptions(Options::MODE_EDIT))
                        ->validate($arrDefaultParam, true),
                    $id
                );
                break;
            case 'files':
                if(!$id)
                {
                    $data['status'] = array(
                        "errorcode" => "WRONG_PARAMETER",
                        "message"   => "Please pass the file ID to be edited"
                    );
                    break;
                }

                $arrDefaultParam['fileId'] = $id;

                $data = $this->call(
                    onOfficeSDK::ACTION_ID_MODIFY,
                    'file',
                    (new FileOptions(Options::MODE_EDIT))
                        ->validate($arrDefaultParam, true),
                    $id
                );
                break;
        }

        if ($asArray)
        {
            return $data;
        }

        return new JsonResponse($data);
    }
}
