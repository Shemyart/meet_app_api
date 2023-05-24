<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\PersonRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PersonRepository implements PersonRepositoryInterface
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function all(): \Illuminate\Support\Collection
    {
        $user = auth()->user();

        $like = DB::table('like')
            ->where('user_id', $user->id)
            ->pluck('person_id')->toArray();
        $dislike = DB::table('dislike')
            ->where('user_id', $user->id)
            ->pluck('person_id')->toArray();

        $persons = DB::table('users')->where('id', '!=', $user->id)
            //->where('gender', '!=', $user->gender)
            ->where('city', $user->city)
            ->whereBetween('age', [(int)$user->age - 2, (int)$user->age + 2])
            ->whereNotIn('user_id', $like)
            ->whereNotIn('user_id', $dislike)
            ->get();
        return $persons;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getById($id)
    {
        $persons = DB::table('users')
            ->where('id', '=', $id)
            ->first();
        return $persons;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getMatches(): \Illuminate\Support\Collection
    {
        $user = auth()->user();
        $matches = DB::table('matches')
            ->where('user_id', $user->id)
            ->pluck('match_user_id');
        $persons = DB::table('users')
            ->whereIn('id', $matches)
            ->get();
        return $persons;
    }

    /**
     * Метод добавляет выбранного пользователя в списко понравившихся
     * @param $id
     * @return bool
     */
    public function like($id):bool
    {
        $user = auth()->user();
        $add = DB::table('like')
            ->where('user_id', $user->id)
            ->updateOrInsert(
                ['person_id' => $id],
                [
                    'user_id' => $user->id,
                    'person_id' => $id
                ]
            );
        return $add;
    }

    /**
     * Метод добавляет выбранного пользователя в списко не понравившихся
     * @param $id
     * @return bool
     */
    public function dislike($id):bool
    {
        $user = auth()->user();
        $add = DB::table('dislike')
            ->where('user_id', $user->id)
            ->updateOrInsert(
                ['person_id' => $id],
                [
                    'user_id' => $user->id,
                    'person_id' => $id
                ]
            );
        return $add;
    }

    /**
     * @param $id
     * @return bool
     */
    public function checkMatch($id): bool
    {
        $user = auth()->user();
        $check = DB::table('like')
            ->where('user_id', $id)
            ->where('person_id', $user->id)
            ->get();
        if ($check->isNotEmpty()) {
            DB::table('matches')
                ->insert([
                    'user_id' => $user->id,
                    'match_person_id' => $id
                ]);
            DB::table('matches')
                ->insert([
                    'user_id' => $id,
                    'match_person_id' => $user->id
                ]);
            return true;
        }
        return false;
    }
}
