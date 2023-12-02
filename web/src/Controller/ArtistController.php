<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/artist')]
class ArtistController extends AbstractController
{
    #[Route('/', name: 'app_artist_index', methods: ['GET'])]
    #[Template('artist/index.html.twig')]
    public function index(ArtistRepository $artistRepository): array
    {
        return [
            'artists' => $artistRepository->findAll(),
        ];
    }

    /**
     * @throws FilesystemException
     */
    #[Route('/new', name: 'app_artist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderService $uploaderService): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedArtistPicture = $form->get('profile_picture')->getData();

            if ($uploadedArtistPicture) {
                $artist_directory = 'artist_pictures';
                $artist_picture_path = $uploaderService->uploadFile($uploadedArtistPicture, $artist_directory, true);
                $artist->setPicture($artist_directory.'/'.$artist_picture_path);
            }

            if ($uploadedArtistPicture && is_file($uploadedArtistPicture->getPathname())) {
                unlink($uploadedArtistPicture->getPathname());
            }

            $albumsCollection = $form->get('albums');

            foreach ($albumsCollection as $albumForm) {
                $uploadedAlbumCover = $albumForm->get('album_cover')->getData();

                $album_directory = 'album_pictures';
                $album_picture_path = $uploaderService->uploadFile($uploadedAlbumCover, $album_directory, true);

                if ($uploadedAlbumCover && is_file($uploadedAlbumCover->getPathname())) {
                    unlink($uploadedAlbumCover->getPathname());
                }

                $albumForm->getData()->setPicture($album_directory.'/'.$album_picture_path);
            }

            $entityManager->persist($artist);
            $entityManager->flush();

            $this->addFlash(
                'success',
                '<strong>Success!</strong> Artist is added.'
            );

            return $this->redirectToRoute('app_artist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('artist/new.html.twig', [
            'artist' => null,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artist_show', methods: ['GET'])]
    #[Template('artist/show.html.twig')]
    public function show(Artist $artist): array
    {
        return [
            'artist' => $artist,
        ];
    }

    #[Route('/{id}/edit', name: 'app_artist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Artist $artist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_artist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artist/edit.html.twig', [
            'artist' => $artist,
            'form' => $form,
        ]);
    }

    /**
     * @throws FilesystemException
     */
    #[Route('/{id}', name: 'app_artist_delete', methods: ['POST'])]
    public function delete(Request $request, Artist $artist, EntityManagerInterface $entityManager, UploaderService $uploaderService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artist->getId(), $request->request->get('_token'))) {
            $entityManager->remove($artist);
            $entityManager->flush();

            if ($artist->getPicture()) {
                $uploaderService->deleteFile($artist->getPicture());
            }
        }

        return $this->redirectToRoute('app_artist_index', [], Response::HTTP_SEE_OTHER);
    }
}
