<?php

namespace App\Services;

interface IContributorService {
    public function getRandomAuthor();
    public function findById($id);
}

