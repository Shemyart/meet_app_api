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
            ->where('age', '<', (int)$user->age + 3)
            ->get();
        return $persons;
    }

    public function getById($id){
        $persons = DB::table('users')
            ->where('id', '=', $id)
            ->first();
        return $persons;
    }
}
