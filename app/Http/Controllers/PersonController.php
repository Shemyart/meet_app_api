<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonResource;
use App\Repositories\Interfaces\PersonRepositoryInterface;

class PersonController
{
    private $personRepository;
    public function __construct( PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * Получить профили на выбор
     */
    public function getAll(){
        $persons = $this->personRepository->all();
        if($persons == null){
            return response()->json([
                'message' => 'Профили закончились'
            ]);
        }
        return response()->json([
           'persons' => PersonResource::collection($persons)
        ]);
    }

    /**
     * Получить подробную информацию по профилю
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById($id){
        $person = $this->personRepository->getById($id);
        if($person == null){
            return response()->json([
                'message' => 'Такого профиля не существует'
            ]);
        }
        return response()->json([
            'person' => new PersonResource($person)
        ]);
    }

    /**
     * Получить список совпадений
     */
    public function matches(){

    }
}
