<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class QuizLogger
{
    private ?LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logHomePageVisit(): void
    {
        $this->logger->info("User visited the homepage");
    }

    public function logQuizStart(): void
    {
        $this->logger->info("User started the quiz");
    }

    public function logQuestionDisplayed(int $questionNumber): void
    {
        $this->logger->info("Showing question to user", [
            "questionNumber" => $questionNumber,
        ]);
    }

    public function logAnswer(int $questionNumber, string $selectedAnswer, string $correctAnswer): void
    {
        $this->logger->info("User answered question", [
            "questionNumber" => $questionNumber,
            "selectedAnswer" => $selectedAnswer,
            "correctAnswer" => $correctAnswer,
            'is_correct' => $selectedAnswer === $correctAnswer,
        ]);
    }

    public function logQuizFinished(int $score, int $total): void
    {
        $this->logger->info("User finished quiz", [
            "score" => $score,
            "total" => $total,
        ]);
    }
}