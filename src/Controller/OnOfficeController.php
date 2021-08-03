<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller;

use Oveleon\ContaoOnofficeApiBundle\OnOfficeEdit;
use Oveleon\ContaoOnofficeApiBundle\OnOfficeRead;
use Oveleon\ContaoOnofficeApiBundle\OnOfficeUpload;
use Oveleon\ContaoOnofficeApiBundle\OnOfficeCreate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Handles the onOffice api routes.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 * @author Daniele Sciannimanica <daniele@oveleon.de>
 */
class OnOfficeController extends AbstractController
{
    /**
     * Runs the command scheduler. (READ)
     *
     * @return JsonResponse
     */
    public function readAction($version, $module, $id)
    {
        $this->container->get('contao.framework')->initialize();

        $controller = new OnOfficeRead();
        $data = $controller->run($module, $id);

        return new JsonResponse($data);
    }

    /**
     * Runs the command scheduler. (EDIT)
     *
     * @return JsonResponse
     */
    public function editAction($version, $module, $id)
    {
        $this->container->get('contao.framework')->initialize();

        $controller = new OnOfficeEdit();
        $data = $controller->run($module, $id);

        return new JsonResponse($data);
    }

    /**
     * Runs the command scheduler. (UPLOAD)
     *
     * @return JsonResponse
     */
    public function uploadAction($version, $module, $id)
    {
        $this->container->get('contao.framework')->initialize();

        $controller = new OnOfficeUpload();
        $data = $controller->run($module, $id);

        return new JsonResponse($data);
    }

    /**
     * Runs the command scheduler. (CREATE)
     *
     * @return JsonResponse
     */
    public function createAction($version, $module)
    {
        $this->container->get('contao.framework')->initialize();

        $controller = new OnOfficeCreate();
        $data = $controller->run($module);

        return new JsonResponse($data);
    }
}
