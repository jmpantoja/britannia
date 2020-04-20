<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Controller\Admin;


use Britannia\Infraestructure\Symfony\Service\FileUpload\PhotoUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class PhotoController extends AbstractController
{

    /**
     * @var PhotoUploader
     */
    private PhotoUploader $uploader;

    public function __construct(PhotoUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function upload(Request $request)
    {
        $this->denyAccessUnlessGranted(['ROLE_MANAGER', 'ROLE_RECEPTION']);

        $file = $request->files->get('file');

        $upload = $this->uploader->uploadFile($file);


        return $this->json($upload->toArray());
    }

    public function download(Request $request)
    {
        $this->denyAccessUnlessGranted(['ROLE_SONATA_ADMIN']);

        $pathToFile = $request->get('path_to_file');
        $path = $this->uploader->makePathAbsolute($pathToFile);

        return BinaryFileResponse::create($path)
            ->setContentDisposition('inline');
    }

}
