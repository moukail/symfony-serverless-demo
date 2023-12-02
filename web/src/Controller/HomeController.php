<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AlbumRepository $albumRepository, LoggerInterface $logger): Response
    {
        $albums = $albumRepository->findAll();

        $logger->critical('success', ['app_home']);

        return $this->render('home/index.html.twig', [
            'albums' => $albums,
        ]);
    }
}
