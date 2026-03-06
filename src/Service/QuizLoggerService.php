<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuizLoggerService
{
    private $session;
    private $logger;

    public function __construct(SessionInterface $session, LoggerInterface $logger)
    {
        $this->session = $session;
        $this->logger = $logger;
    }

    public function getSessionId(): string
    {
        return $this->session->getId();
    }

    public function logHomePageVisit(): void
    {
        $this->logger->info("User visited the homepage", [
            'session_id' => $this->getSessionId(),
        ]);
    }

    public function logQuizStart(): void
    {
        $this->logger->info("User started the quiz", [
            'session_id' => $this->getSessionId(),
        ]);
    }

    public function logQuestionDisplayed(int $questionNumber): void
    {
        $this->logger->info("Showing question to user", [
            'session_id' => $this->getSessionId(),
            "questionNumber" => $questionNumber,
        ]);
    }

    public function logAnswer(int $questionNumber, string $selectedAnswer, string $correctAnswer): void
    {
        $this->logger->info("User answered question", [
            'session_id' => $this->getSessionId(),
            "questionNumber" => $questionNumber,
            "selectedAnswer" => $selectedAnswer,
            "correctAnswer" => $correctAnswer,
            'is_correct' => $selectedAnswer === $correctAnswer,
        ]);
    }

    public function logQuizFinished(int $score, int $total): void
    {
        $this->logger->info("User finished quiz", [
            'session_id' => $this->getSessionId(),
            "score" => $score,
            "total" => $total,
        ]);
    }

    public function logError(string $message, array $context = []): void
    {
        $context['session_id'] = $this->getSessionId();

        $this->logger->error($message, $context);
    }
}