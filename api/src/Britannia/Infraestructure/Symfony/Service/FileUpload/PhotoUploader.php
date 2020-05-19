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

namespace Britannia\Infraestructure\Symfony\Service\FileUpload;


use Britannia\Domain\Entity\Image\Image;
use Britannia\Domain\Entity\Staff\Photo;
use Britannia\Domain\VO\Attachment\FileInfo;
use Impulze\Bundle\InterventionImageBundle\ImageManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PhotoUploader extends FileUploader
{
    private $targetDir;
    /**
     * @var ImageManager
     */
    private ImageManager $imageManager;
    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $router;

    public function __construct(ParameterBagInterface $parameterBag, UrlGeneratorInterface $router, ImageManager $imageManager)
    {
        $this->targetDir = $parameterBag->get('photos_dir');
        $this->router = $router;
        $this->imageManager = $imageManager;
    }

    public function targetDir(): string
    {
        return $this->targetDir;
    }

    protected function upload(UploadedFile $file): FileInfo
    {
        $this->imageManager->make($file->getPathname())
            ->fit(200, 266)
            ->save($file->getPathname());

        return parent::upload($file);
    }

    public function uploadUrl(): string
    {
        return $this->router->generate('photo_upload');
    }

    public function downloadUrl(string $pathToFile = null): string
    {
        return $this->router->generate('photo_download', [
            'path_to_file' => $pathToFile
        ]);
    }

    public function photoUrl(?Image $image): ?string {
        if(is_null($image)){
            return $this->downloadUrl('shape.jpg');
        }

        return $this->downloadUrl($image->path());
    }
}
