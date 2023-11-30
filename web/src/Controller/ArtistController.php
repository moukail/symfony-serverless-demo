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
            $uploadedPicture = $form->get('profile_picture')->getData();

            if ($uploadedPicture) {
                $directory = 'artist_pictures';
                $picturePath = $uploaderService->uploadFile($uploadedPicture, $directory, true);
                $artist->setPicture($directory.'/'.$picturePath);
            }

            $entityManager->persist($artist);
            $entityManager->flush();

            if ($uploadedPicture && is_file($uploadedPicture->getPathname())) {
                unlink($uploadedPicture->getPathname());
            }

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
