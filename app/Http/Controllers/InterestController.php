<?php

namespace App\Http\Controllers;

use App\Http\Resources\InterestResource;
use App\Repositories\Interfaces\InterestRepositoryInterface;
use Illuminate\Http\JsonResponse;

class InterestController extends Controller
{
    private $interestRepository;

    public function __construct(InterestRepositoryInterface $interestRepository)
    {
        $this->interestRepository = $interestRepository;

    }

    /**
     * Получить все теги интересов
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $interests = $this->interestRepository->getAll();
        if ($interests->isNotEmpty()) {
            return response()->json([
                'interests' => InterestResource::collection($interests),
            ]);
        } else {
            return response()->json([
                'message' => 'Интересов нет'
            ]);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function chooseInterest($id): JsonResponse
    {
        $choose = $this->interestRepository->chooseInterest($id);
        if ($choose) {
            return response()->json([
                'message' => 'Успешно добавлено',
            ]);
        } else {
            return response()->json([
                'message' => 'Интересов нет'
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getByUser(): JsonResponse
    {
        $interests = $this->interestRepository->getByUser();
        if ($interests->isNotEmpty()) {
            return response()->json([
                'interests' => InterestResource::collection($interests),
            ]);
        } else {
            return response()->json([
                'message' => 'Интересов нет'
            ]);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getByPerson($id): JsonResponse
    {
        $interests = $this->interestRepository->getByPerson($id);
        if ($interests->isNotEmpty()) {
            return response()->json([
                'interests' => InterestResource::collection($interests),
            ]);
        } else {
            return response()->json([
                'message' => 'Интересов нет'
            ]);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $delete = $this->interestRepository->delete($id);
        if ($delete) {
            return response()->json([
                'message' => 'Удалено успешно'
            ]);
        } else {
            return response()->json([
                'message' => 'Интересов нет'
            ]);
        }
    }

}
