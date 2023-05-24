<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonResource;
use App\Repositories\Interfaces\PersonRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonController
{
    private $personRepository;

    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * Получить профили на выбор
     * @return JsonResponse
     */
    public function getAll(Request $request): JsonResponse
    {
        $persons = $this->personRepository->all($request);
        if ($persons == null) {
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
     * @return JsonResponse
     */
    public function getById($id): JsonResponse
    {
        $person = $this->personRepository->getById($id);
        if ($person == null) {
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
     * @return JsonResponse
     */
    public function matches(): JsonResponse
    {
        $persons = $this->personRepository->getMatches();
        if ($persons->isEmpty()) {
            return response()->json([
                'message' => 'Список пуст'
            ]);
        }
        return response()->json([
            'persons' => PersonResource::collection($persons)
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function like($id): JsonResponse
    {
        $like = $this->personRepository->like($id);
        if ($like) {
            $checkMatch = $this->personRepository->checkMatch($id);
            if ($checkMatch) {
                return response()->json([
                    'message' => 'Совпадение'
                ]);
            }
        }
        return response()->json([
            'message' => 'Добавлено успешно'
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function dislike($id): JsonResponse
    {
        $dislike = $this->personRepository->dislike($id);
        if ($dislike) {
            return response()->json([
                'message' => 'Добавлено успешно'
            ]);
        }
        return response()->json([
            'message' => 'Ошибка'
        ]);

    }
}
