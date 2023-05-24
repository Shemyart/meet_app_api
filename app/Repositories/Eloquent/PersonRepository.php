<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\PersonRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PersonRepository implements PersonRepositoryInterface
{
    const ZERO_AGE = 0;
    const MAX_AGE = 100;
    const DEFAULT_GENDER = 'М';

    /**
     * @param $request
     * @return Collection
     */
    public function all($request): Collection
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
            ->where('city', $user->city);
            !empty($like) ? $persons->whereNotIn('id', $like) : null;
            !empty($dislike) ? $persons->whereNotIn('id', $dislike) : null;
        $persons = $this->filtration($persons, $request);

        $persons = $persons->get();
        return $persons;
    }

    public function filtration($persons, $request){
        $minAge = $request['minAge'] ?? self::ZERO_AGE;
        $maxAge = $request['maxAge'] ?? self::MAX_AGE;
        $gender = $request['gender'] ?? self::DEFAULT_GENDER;

        $persons = $persons->whereBetween('age', [$minAge, $maxAge]);
        $persons = $persons->where('gender', $gender);
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
     * @return Collection
     */
    public function getMatches(): Collection
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
