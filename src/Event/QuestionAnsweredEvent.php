<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class QuestionAnsweredEvent extends Event
{
    public const NAME = 'quiz.question_answered';

    private string $sessionId;
    private int $questionNumber;
    private string $selectedAnswer;
    private string $correctAnswer;
    private bool $isCorrect;

    public function __construct(
        string $sessionId,
        int $questionNumber,
        string $selectedAnswer,
        string $correctAnswer,
        bool $isCorrect
    ) {
        $this->sessionId = $sessionId;
        $this->questionNumber = $questionNumber;
        $this->selectedAnswer = $selectedAnswer;
        $this->correctAnswer = $correctAnswer;
        $this->isCorrect = $isCorrect;
    }

    public function getSessionId(): string { return $this->sessionId; }
    public function getQuestionNumber(): int { return $this->questionNumber; }
    public function getSelectedAnswer(): string { return $this->selectedAnswer; }
    public function getCorrectAnswer(): string { return $this->correctAnswer; }
    public function isCorrect(): bool { return $this->isCorrect; }
}