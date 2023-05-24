<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Редактировать профиль пользователя
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string',
            'city' => 'string',
            'country' => 'string',
            'description' => 'text',
            'gender' => 'string',
            'date_of_birth' => 'date',
            'age' => 'integer'
        ]);
        $user = auth()->user();
        $name = $request['name'] ?? $user->name;
        $city = $request['city'] ?? $user->city;
        $country = $request['country'] ?? $user->country;
        $description = $request['description'] ?? $user->description;
        $gender = $request['gender'] ?? $user->gender;
        $dateOfBirth = $request['date_of_birth'] ?? $user->date_of_birth;
        $age = $request['age'] ?? $user->age;

        $update = User::where('id', $user->id)
            ->update([
                'name' => $name,
                'city' => $city,
                'country' => $country,
                'description' => $description,
                'gender' => $gender,
                'date_of_birth' => $dateOfBirth,
                'age' => $age,
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        if(!is_null($update)){
            return response()->json(auth()->user());
        }
        return response()->json([
            'message' => 'Произошла ошибка'
        ], 400);
    }

    /**
     * Удалить профиль пользователя
     */
    public function delete(){
        //
    }
}
