<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuizGameController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('quiz_game/home.html.twig');
    }

//    #[Route("/questions", name: 'app_questions')]
//    public function questions(): Response
//    {
//        return $this->render('quiz_game/questions.html.twig');
//    }
//
//    #[Route("/results", name: 'app_results')]
//    public function results(): Response
//    {
//        return $this->render('quiz_game/results.html.twig');
//    }
}
