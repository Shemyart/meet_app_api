<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface PersonRepositoryInterface
{
    public function all($request);

    public function getById($id);

    public function getMatches();

    public function like($id);

    public function dislike($id);

    public function checkMatch($id);

}
