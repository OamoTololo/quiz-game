<?php

namespace App\EventListener;

use App\Event\QuestionAnsweredEvent;
use App\Event\QuizFinishedEvent;
use App\Event\QuizStartedEvent;
use Psr\Log\LoggerInterface;

class QuizLoggerListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onQuizStarted(QuizStartedEvent $event): void
    {
        $this->logger->info('Quiz started', ['session_id' => $event->getSessionId()]);
    }

    public function onQuestionAnswered(QuestionAnsweredEvent $event): void
    {
        $this->logger->info('Question answered', [
            'session_id' => $event->getSessionId(),
            'question_number' => $event->getQuestionNumber(),
            'selected_answer' => $event->getSelectedAnswer(),
            'correct_answer' => $event->getCorrectAnswer(),
            'is_correct' => $event->isCorrect(),
        ]);
    }

    public function onQuizFinished(QuizFinishedEvent $event): void
    {
        $this->logger->info('Quiz finished', [
            'session_id' => $event->getSessionId(),
            'score' => $event->getScore(),
            'total_questions' => $event->getTotalQuestions(),
        ]);
    }
}