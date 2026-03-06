<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class QuizFinishedEvent extends Event
{
    public const NAME = 'quiz.finished';

    private string $sessionId;
    private int $score;
    private int $totalQuestions;

    public function __construct(string $sessionId, int $score, int $totalQuestions)
    {
        $this->sessionId = $sessionId;
        $this->score = $score;
        $this->totalQuestions = $totalQuestions;
    }

    public function getSessionId(): string { return $this->sessionId; }
    public function getScore(): int { return $this->score; }
    public function getTotalQuestions(): int { return $this->totalQuestions; }
}