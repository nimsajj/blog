<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Psr\Log\LoggerInterface;

class FileUploader
{
    private $targetDirectory;
    private $slugger;
    private $logger;
    private $filesystem;

    public function __construct($targetDirectory, SluggerInterface $slugger, LoggerInterface $logger, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->logger = $logger;
        $this->filesystem = $filesystem;
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            $this->logger->error('Failed to upload file: ' . $e->getMessage());
            throw new FileException("Failed to upload file");
        }

        return $fileName;
    }

    public function remove(string $fileName): void
    {
        $this->filesystem->remove($this->targetDirectory . "/" . $fileName);
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
