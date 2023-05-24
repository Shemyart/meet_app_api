<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\PersonRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PersonRepository implements PersonRepositoryInterface
{

    public function all()
    {
        $user = auth()->user();
        $persons = DB::table('users')->where('id', '!=', $user->id)
            //->where('gender', '!=', $user->gender)
            ->where('city', $user->city)
            ->whereBetween('age', [(int)$user->age -2,  (int)$user->age + 2])
            ->get();
        return $persons;
    }

    public function getById($id){
        $persons = DB::table('users')
            ->where('id', '=', $id)
            ->first();
        return $persons;
    }

    public function getMatches(){
        $user = auth()->user();
        $matches = DB::table('matches')
            ->where('user_id', $user->id)
            ->pluck('match_user_id');
        $persons = DB::table('users')
            ->whereIn('id', $matches)
            ->get();
        return $persons;
    }
}
