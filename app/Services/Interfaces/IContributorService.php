<?php

namespace App\Services\Interfaces;

interface IContributorService {
    public function getRandomAuthor();
    public function findById($id);
}

