<?php

namespace App\Services;

interface IContributorService
{
    public function all();
    public function getRandomAuthor();
    public function findById($id);
}
