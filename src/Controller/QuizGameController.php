<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuizGameController extends AbstractController
{
    #[Route('/', name: 'app_quiz_game')]
    public function index(): Response
    {
        return $this->render('quiz_game/index.html.twig', [
            'controller_name' => 'QuizGameController',
        ]);
    }
}
