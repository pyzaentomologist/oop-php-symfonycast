<?php

namespace App\Comment;

use App\Entity\Comment;

class CommentSpamManager
{
    private CommentSpamCounterInterface $spamWordCounter;

    public function __construct(CommentSpamCounterInterface $spamWordCounter)
    {
        $this->spamWordCounter = $spamWordCounter;
    }
    
    public function validate(Comment $comment): void
    {
        $content = $comment->getContent();
        $badWordsCount = $this->spamWordCounter->countSpamWords($content);
        if ($badWordsCount >= 2) {
            throw new \Exception('Message detected as spam');
        }
    }
}