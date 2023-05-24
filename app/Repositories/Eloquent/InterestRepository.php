<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\InterestRepositoryInterface;
use App\Repositories\Interfaces\PersonRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InterestRepository implements InterestRepositoryInterface
{

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        $interests = DB::table('interests')
            ->orderBy('count', 'DESC')
            ->get();
        return $interests;
    }

    /**
     * @param $id
     * @return bool
     */
    public function chooseInterest($id): bool
    {
        $user = auth()->user();
        $return = DB::table('user_interests')
            ->updateOrInsert(
                [ 'user_id' => $user->id , 'interest_id' => $id ],
                [ 'user_id' => $user->id , 'interest_id' => $id ],
            );
        DB::table('interests')
            ->where('id', $id)
            ->increment('count');
        return $return;
    }

    /**
     * @return Collection
     */
    public function getByUser(): Collection
    {
        $user = auth()->user();
        $usersInterests = DB::table('user_interests')
            ->where('user_id', $user->id)
            ->pluck('interest_id')->toArray();

        $interests = DB::table('interests')
            ->whereIn('id', $usersInterests)
            ->get();
        return $interests;
    }

    /**
     * @param $id
     * @return Collection
     */
    public function getByPerson($id): Collection
    {
        $usersInterests = DB::table('user_interests')
            ->where('user_id', $id)
            ->pluck('interest_id')->toArray();

        $interests = DB::table('interests')
            ->whereIn('id', $usersInterests)
            ->get();
        return $interests;
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id): int
    {
        $user = auth()->user();
        return DB::table('user_interests')
            ->where('user_id', $user->id)
            ->where('interest_id', $id)
            ->delete();
    }




}
