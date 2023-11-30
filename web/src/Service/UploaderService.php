<?php

namespace App\Service;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Visibility;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class UploaderService
{
    public function __construct(private FilesystemOperator $uploadsStorageLazy, private SluggerInterface $slugger)
    {
    }

    /**
     * @throws FilesystemException
     */
    public function uploadFile(File $file, string $directory, bool $isPublic): string
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }

        $originalFilename = pathinfo($originalFilename, PATHINFO_FILENAME);

        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        $stream = fopen($file->getPathname(), 'r');

        $this->uploadsStorageLazy->writeStream(
            $directory.'/'.$newFilename,
            $stream,
            [
                'visibility' => $isPublic ? Visibility::PUBLIC : Visibility::PRIVATE,
            ]
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $newFilename;
    }

    /**
     * @throws FilesystemException
     */
    public function readStream(string $path)
    {
        return $this->uploadsStorageLazy->readStream($path);
    }

    /**
     * @throws FilesystemException
     */
    public function read(string $path): string
    {
        return $this->uploadsStorageLazy->read($path);
    }

    /**
     * @throws FilesystemException
     */
    public function deleteFile(string $path): void
    {
        $this->uploadsStorageLazy->delete($path);
    }
}
