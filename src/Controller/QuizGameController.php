<?php

namespace App\Controller;

use App\Event\QuestionAnsweredEvent;
use App\Event\QuizStartedEvent;
use App\Service\QuizLoggerService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class QuizGameController extends AbstractController
{
    #[Route('/', name: 'quiz_home')]
    public function home(QuizLoggerService $quizLogger): Response
    {
        $quizLogger->logHomePageVisit();

        return $this->render('quiz_game/home.html.twig');
    }

    #[Route("/quiz", name: 'quiz_start')]
    public function startQuiz(SessionInterface $session, EventDispatcher $dispatcher): Response
    {
        // Reset session
        $session->set('score', 0);
        $session->set('current_question', 0);

        // Dispatch quiz started event
        $dispatcher->dispatch(
            new QuizStartedEvent($session->getId()),
            QuizStartedEvent::class
        );

        return $this->redirectToRoute('quiz_question');
    }

    #[Route("/quiz/question", name: 'quiz_question')]
    public function quizQuestion(Request $request, EventDispatcher $dispatcher): Response
    {
        $session = $request->getSession();
        $questions = $this->getQuestions();
        $currentIndex = $session->get('current_question', 0);

        // Error protection
        if (!isset($questions[$currentIndex])) {
            return $this->redirectToRoute('quiz_results');
        }

        return $this->render('quiz_game/question.html.twig', [
            'question' => $questions[$currentIndex],
            'questionNumber' => $currentIndex + 1,
            'totalQuestions' => count($questions),
            'score' => $session->get('score'),
        ]);
    }

    #[Route("/quiz/answer", name: 'quiz_answer', methods: ['POST'])]
    public function answer(
        Request $request,
        SessionInterface $session,
        EventDispatcher $dispatcher
    ): Response
    {
        $questions = $this->getQuestions();
        $currentIndex = $session->get('current_question');

        if (!isset($questions[$currentIndex])) {
            return $this->redirectToRoute('quiz_results');
        }

        $selectedAnswer = $request->request->get('answer');
        $correctAnswer = $questions[$currentIndex]['correct_answer'];
        $isCorrect = $selectedAnswer === $correctAnswer;

        if ($isCorrect) {
            $session->set('score', $session->get('score') + 1);
        }

        $dispatcher->dispatch(
            new QuestionAnsweredEvent(
                $session->getId(),
                $currentIndex + 1,
                $selectedAnswer,
                $correctAnswer,
                $isCorrect
            ),
            QuestionAnsweredEvent::NAME
        );

        $session->set('current_question', $currentIndex + 1);

        return $this->redirectToRoute('quiz_question');
    }

    #[Route("/quiz/results", name: 'quiz_results')]
    public function results(SessionInterface $session, EventDispatcher $dispatcher): Response
    {

        $score = $session->get('score');
        $total = count($this->getQuestions());

        // Dispatch the quiz finished event
        $dispatcher->dispatch(
            new QuizStartedEvent($session->getId(), $score, $total),
            QuizStartedEvent::class
        );

        return $this->render('quiz_game/results.html.twig', [
            'score' => $score,
            'total' => $total,
        ]);
    }

    #[Route("/quiz/restart", name: 'quiz_restart')]
    public function restartQuiz(SessionInterface $session, QuizLoggerService $quizLogger): Response
    {
        // Reset the quiz
        $session->set('score', 0);
        $session->set('current_question', 0);

        // Log the restart
        $quizLogger->logQuizRestart();

        return $this->redirectToRoute('quiz_question');
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
