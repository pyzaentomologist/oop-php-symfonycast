<?php

namespace App\Comment;

interface CommentSpamCounterInterface
{
    public function countSpamWords(string $content): int;
}