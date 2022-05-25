<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/app', name: 'app_index')]
    public function app(): Response
    {
        return $this->json([
            'message' => 'App!',
            'path' => 'src/Controller/AppController.php',
        ]);
    }
}
