<?php

namespace App\Http\Controllers;

use App\Services\RecognitionService;

use App\Http\Requests\Recognition\RecognitionUpdateBox;
use App\Http\Requests\Recognition\RecognitionShowReport;
use App\Http\Requests\Recognition\RecognitionShowPrevRecognition;
use App\Http\Requests\Recognition\RecognitionUpdateCheckingRecognition;
use App\Repositories\BoxRecognitionRepository;

class RecognitionController extends Controller
{

    /**
     * @var RecognitionService
     */
    protected $recognitionService;

    public function __construct(RecognitionService $recognitionService)
    {
        $this->recognitionService = $recognitionService;
    }

    /**
     * Отображаем страницу распознаваний
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('recognitions')->with([
            'data' => $this->recognitionService->getFilterList(),
        ]);
    }

    /**
     * Отправляем данные для отчета (выбор камеры,
     * проверка ящиков, проверка работы ревизора, ручной подсчет)
     * @param RecognitionShowReport $request
     * @return \Illuminate\Support\Collection
     */
    public function showReport(RecognitionShowReport $request)
    {
        return $this->recognitionService->showReport($request);
    }

    /**
     * Получаем предыдущее распознавания
     * @param RecognitionShowPrevRecognition $request
     * @return \Illuminate\Support\Collection
     */
    public function showPrewRecognition(RecognitionShowPrevRecognition $request)
    {
        return $this->recognitionService->getPrevRecognition($request);
    }

    /**
     * Меняем статус (checking) у выгруженных распознаваний
     * @param RecognitionUpdateCheckingRecognition $request
     * @return \Illuminate\Support\Collection
     */
    public function updateCheckingRecognition(RecognitionUpdateCheckingRecognition $request)
    {
        return $this->recognitionService->updateCheckingRecognition($request);
    }

    /**
     * Редактируем, добавляем, удаляем ящик(и)
     * @param RecognitionUpdateBox $request
     * @return \Illuminate\Support\Collection
     */
    public function updateBox(RecognitionUpdateBox $request)
    {
        return $this->recognitionService->updateBoxes($request);
    }
}
