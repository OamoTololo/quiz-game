<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class QuizStartedEvent extends Event
{
    public const NAME = 'quiz.started';
    private string $sessionId;

    public function __construct(string $sessionId)
    {
        $this->sessionId = $sessionId;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }
}