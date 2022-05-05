<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller;

use Oveleon\ContaoOnofficeApiBundle\Authenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Handles the onOffice api routes, authentication and switch between versions.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 * @author Daniele Sciannimanica <daniele@oveleon.de>
 *
 * @Route(defaults={"_scope" = "frontend", "_token_check" = false})
 */
class OnOfficeController extends AbstractController
{
    /**
     * Read Controller
     *
     * @Route(
     *     "/api/onoffice/v{version}/{module}/{id}",
     *     name="onoffice_read",
     *     defaults={"id" = null},
     *     methods={"GET"}
     * )
     */
    public function read(int $version, string $module, ?int $id): JsonResponse
    {
        $this->container->get('contao.framework')->initialize();

        if(!(new Authenticator())->isGranted())
        {
            return new JsonResponse([
                'errorcode' => 'NOT_AUTHENTICATED',
                'message' => 'An valid API key is required'
            ]);
        }

        return (new ReadController())->run($module, $id);
    }

    /**
     * Edit Controller
     *
     * @Route(
     *     "/api/onoffice/v{version}/{module}/{id}/edit",
     *     name="onoffice_edit",
     *     methods={"POST"}
     * )
     */
    public function edit(int $version, string $module, int $id): JsonResponse
    {
        $this->container->get('contao.framework')->initialize();

        if(!(new Authenticator())->isGranted())
        {
            return new JsonResponse([
                'errorcode' => 'NOT_AUTHENTICATED',
                'message' => 'An valid API key is required'
            ]);
        }

        return (new EditController())->run($module, $id);
    }

    /**
     * Upload Controller
     *
     * @Route(
     *     "/api/onoffice/v{version}/{module}/{id}/upload",
     *     name="onoffice_upload",
     *     methods={"POST"}
     * )
     */
    public function upload(int $version, string $module, int $id): JsonResponse
    {
        $this->container->get('contao.framework')->initialize();

        if(!(new Authenticator())->isGranted())
        {
            return new JsonResponse([
                'errorcode' => 'NOT_AUTHENTICATED',
                'message' => 'An valid API key is required'
            ]);
        }

        return (new UploadController())->run($module, $id);
    }

    /**
     * Create Controller
     *
     * @Route(
     *     "/api/onoffice/v{version}/{module}/create",
     *     name="onoffice_create",
     *     methods={"POST"}
     * )
     */
    public function create(int $version, string $module): JsonResponse
    {
        $this->container->get('contao.framework')->initialize();

        if(!(new Authenticator())->isGranted())
        {
            return new JsonResponse([
                'errorcode' => 'NOT_AUTHENTICATED',
                'message' => 'An valid API key is required'
            ]);
        }

        return (new CreateController())->run($module);
    }
}
