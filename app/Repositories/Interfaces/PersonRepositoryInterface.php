<?php

namespace App\Repositories\Interfaces;

interface PersonRepositoryInterface
{
    public function all();

    public function getById($id);

    public function getMatches();

    public function like($id);

    public function dislike($id);

    public function checkMatch($id);

}
