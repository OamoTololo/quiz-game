<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class QuizGameController extends AbstractController
{
    #[Route('/', name: 'quiz_home')]
    public function home(LoggerInterface $logger): Response
    {
        $logger->info('User visited the homepage');

        return $this->render('quiz_game/home.html.twig');
    }

    #[Route("/quiz", name: 'quiz_start')]
    public function startQuiz(SessionInterface $session, LoggerInterface $logger): Response
    {
        // Reset session
        $session->set('score', 0);
        $session->set('current_question', 0);

        $logger->info('User started the quiz');

        return $this->redirectToRoute('quiz_question');
    }

    #[Route("/quiz/question", name: 'quiz_question')]
    public function quizQuestion(Request $request, LoggerInterface $logger): Response
    {
        $session = $request->getSession();

        $questions = $this->getQuestions();

        $currentIndex = $session->get('current_question', 0);

        if (!isset($questions[$currentIndex])) {
            return $this->redirectToRoute('quiz_results');
        }

        $logger->info('Showing question to user', [
            'question_number' => $currentIndex + 1,
        ]);

        return $this->render('quiz_game/question.html.twig', [
            'question' => $questions[$currentIndex],
            'questionNumber' => $currentIndex + 1,
            'totalQuestions' => count($questions),
            'score' => $session->get('score'),
        ]);
    }

    #[Route("/quiz/answer", name: 'quiz_answer', methods: ['POST'])]
    public function answer(Request $request, LoggerInterface $logger): Response
    {
        $session = $request->getSession();

        $questions = $this->getQuestions();

        $currentIndex = $session->get('current_question');

        $selectedAnswer = $request->request->get('answer');

        $isCorrect = $questions[$currentIndex]['correct_answer'] === $selectedAnswer;

        if ($questions[$currentIndex]['correct_answer'] === $selectedAnswer) {
            $session->set('score', $session->get('score') + 1);
        }

        $logger->info('User answered question', [
            'question_number' => $currentIndex + 1,
            'selected_answer' => $selectedAnswer,
            'correct_answer' => $questions[$currentIndex]['correct_answer'],
            'is_correct' => $isCorrect,
        ]);

        $session->set('current_question', $currentIndex + 1);

        return $this->redirectToRoute('quiz_question');
    }

    #[Route("/quiz/results", name: 'quiz_results')]
    public function results(Request $request, LoggerInterface $logger): Response
    {
        $session = $request->getSession();

        $score = $session->get('score');

        $total = count($this->getQuestions());

        $logger->info('User finished quiz', [
            'score' => $score,
            'total' => $total,
        ]);

        return $this->render('quiz_game/results.html.twig', [
            'score' => $score,
            'total' => $total,
        ]);
    }

    public function getQuestions(): array
    {
        return [
            [
                'text' => 'What is 2 + 2?',
                'answers' => ['3', '4', '5', '6'],
                'correct_answer' => '4',
            ],
            [
                'text' => 'What is the capital of France?',
                'answers' => ['Berlin', 'Madrid', 'Paris', 'Rome'],
                'correct_answer' => 'Paris',
            ]
        ];
    }
}
