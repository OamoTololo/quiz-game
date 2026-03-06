<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class QuizLoggerService
{
    private $requestStack;
    private $logger;

    public function __construct(RequestStack $requestStack, LoggerInterface $logger)
    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    private function getSession()
    {
        $request = $this->requestStack->getCurrentRequest();
        return $request ? $request->getSession() : null;
    }

    private function getContext(array $extra = []): array
    {
        $session = $this->getSession();
        return array_merge([
            'session_id' => $session ? $session->getId() : null,
            'username' => $session ? $session->get('username') : null,
            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s'),
        ], $extra);
    }

    public function logHomePageVisit(): void
    {
        $this->logger->info("User visited the homepage", $this->getContext());
    }

    public function logQuizStart(): void
    {
        $this->logger->info("User started the quiz", $this->getContext());
    }

    public function logQuestionDisplayed(int $questionNumber): void
    {
        $this->logger->info("Showing question to user", $this->getContext([
            'questionNumber' => $questionNumber,
        ]));
    }

    public function logAnswer(int $questionNumber, string $selectedAnswer, string $correctAnswer): void
    {
        $this->logger->info("User answered question", $this->getContext([
            'questionNumber' => $questionNumber,
            'selectedAnswer' => $selectedAnswer,
            'correctAnswer' => $correctAnswer,
            'is_correct' => $selectedAnswer === $correctAnswer,
        ]));
    }

    public function logQuizFinished(int $score, int $total): void
    {
        $this->logger->info("User finished quiz", $this->getContext([
            'score' => $score,
            'total' => $total,
        ]));
    }

    public function logError(string $message, array $extra = []): void
    {
        $this->logger->error($message, $this->getContext($extra));
    }
}