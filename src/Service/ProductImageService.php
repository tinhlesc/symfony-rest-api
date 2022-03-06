<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductImageService
{
    /**
     * @var string
     */
    protected $projectDir = null;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(string $projectDir, LoggerInterface $logger)
    {
        $this->projectDir = $projectDir;
        $this->logger = $logger;
    }

    /**
     * @return string|null
     */
    public function upload(UploadedFile $uploadedFile)
    {
        // this is needed to safely include the file name as part of the URL
        $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
        // Move the file to the directory where brochures are stored
        try {
            $uploadedFile->move(
                $this->projectDir.$this->getImagePath(),
                $newFilename
            );
        } catch (FileException $e) {
            $this->logger->error('Can\'t update product image', [$e->getMessage()]);

            return null;
        }

        return $newFilename;
    }

    public function getImagePath(): string
    {
        return '/public/product/images/';
    }
}
