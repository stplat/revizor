<?php

namespace App\Http\Controllers;

use App\Http\Requests\Check\CheckCompareIntermediateTurnout;
use App\Http\Requests\Check\CheckCompareTurnout;
use App\Http\Requests\Check\CheckFilterUiks;
use App\Http\Requests\Check\CheckShowViolations;
use App\Http\Requests\Check\CheckUploadIntermediateVote;
use App\Http\Requests\Check\CheckUploadOfficialVote;
use App\Http\Requests\Check\CheckReviewUik;
use App\Http\Requests\Check\CheckStore;
use App\Http\Requests\Check\CheckDestroy;
use App\Http\Requests\Check\CheckShow;
use App\Http\Requests\Check\CheckUpdate;
use App\Http\Requests\Check\CheckUploadUik;

use App\Services\CheckService;

/**
 * Class CheckController
 * @package App\Http\Controllers
 */
class CheckController extends Controller
{
    /**
     * @var CheckService
     */
    protected $checkService;

    /**
     * CheckController constructor.
     */
    public function __construct()
    {
        $this->checkService = app(CheckService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('checks')->with([
            'data' => collect([
                'checkList' => $this->checkService->getChecks(),
                'authTypeList' => $this->checkService->getAuthTypeList(),
            ])
        ]);
    }

    /**
     * Получение всех проверок
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function all()
    {
        return $this->checkService->getChecks();
    }

    /**
     * Меняем проверку по id
     *
     * @param CheckUpdate $request
     * @return \App\Models\Check
     */
    public function update(CheckUpdate $request): \App\Models\Check
    {
        return $this->checkService->updateCheck($request->all());
    }

    /**
     * Удаление проверки (возвращаем список проверок)
     *
     * @param CheckDestroy $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function destroy(CheckDestroy $request): \Illuminate\Database\Eloquent\Collection
    {
        return $this->checkService->deleteCheck($request);
    }

    /**
     * Создаем новую проверку
     *
     * @param CheckStore $request
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \League\Csv\InvalidArgument
     */
    public function store(CheckStore $request): \Illuminate\Database\Eloquent\Collection
    {
        return $this->checkService->storeCheck($request);
    }

    /**
     * Отображаем проверку.
     *
     * @param CheckShow $request
     * @return \Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function show(CheckShow $request): \Illuminate\Contracts\View\View
    {
        $id = $request->input('check');
        $check = $this->checkService->getCheck($id);


        $modal = app(\App\Services\CheckService::class);

        // dd($modal->getViolationForEventTable((object)[
        //     'check' => 1
        // ])->toArray());


        // dd($modal->findImagesGroupByCameraForModal(7)->toArray());


        // dd($modal->getInitialData((object)[
        //     'check' => 1,
        //     'violation' => 1,
        //     'uik' => 8,
        //     'camera' => 15
        // ])->toArray());

        if (!$check) {
            abort(404);
        }

        $data = collect([
            'check_name' => $check->check_name,
            'check' => $check,
            'uiks' => $this->checkService->getUiks($request),
            'filterList' => $this->checkService->getFilterList($request),
            'violationsForEventTable' => $this->checkService->getViolationForEventTable($request)
        ])->merge($this->checkService->getViolationCams($request));

        return view('check')->with([
            'data' => $data
        ]);
    }

    /**
     * Фильтрация таблицы, диаграммы и карты участков.
     *
     * @param CheckFilterUiks $request
     * @return \Illuminate\Support\Collection
     */
    public function filterUiks(CheckFilterUiks $request): \Illuminate\Support\Collection
    {
        return $this->checkService->getUiks($request);
    }

    /**
     * Фильтрация таблицы событий (violations).
     *
     * @param CheckShowViolations $request
     * @return \Illuminate\Support\Collection
     */
    public function showViolations(CheckShowViolations $request): \Illuminate\Support\Collection
    {
        return $this->checkService->getViolationForEventTable($request);
    }

    /**
     * Загрузка Участков
     *
     * @param CheckUploadUik $request
     * @return void
     * @throws \League\Csv\Exception
     */
    public function uploadUik(CheckUploadUik $request): void
    {
        $this->checkService->uploadUiksByCsv($request);
        //$this->checkService->deleteCheck((object)$request->all());
    }

    /**
     * Проверка Участков
     *
     * @param CheckReviewUik $request
     * @return void
     */
    public function reviewUik(CheckReviewUik $request): void
    {
        $this->checkService->reviewUik($request);
    }

    /**
     * Загрузка официальной явки
     *
     * @param CheckUploadOfficialVote $request
     * @return void
     * @throws \League\Csv\Exception
     */
    public function uploadOfficialVote(CheckUploadOfficialVote $request): void
    {
        $this->checkService->uploadOfficialVote($request);
    }

    /**
     * Сравниваем явку Ревизора с официальной
     * @param CheckCompareTurnout $request
     * @return void
     */
    public function compareTurnout(CheckCompareTurnout $request): void
    {
        $this->checkService->compareTurnout($request);
    }

    /**
     * Сравниваем явку Ревизора с официальной
     * @param CheckUploadIntermediateVote $request
     * @return void
     * @throws \League\Csv\Exception
     */
    public function uploadIntermediateVote(CheckUploadIntermediateVote $request): void
    {
        $this->checkService->uploadIntermediateVote($request);
    }

    /**
     * Сравниваем промужточную явку Ревизора с официальной
     * @param CheckCompareIntermediateTurnout $request
     * @return void
     */
    public function compareIntermediateTurnout(CheckCompareIntermediateTurnout $request): void
    {
        $this->checkService->compareIntermediateTurnout($request);
    }

    /**
     * Отправка Участков на ручной подсчет явки
     *
     * @param CheckReviewUik $request
     * @return void
     */
    public function countingUik(CheckReviewUik $request): void
    {
        $this->checkService->countingUik($request);
    }
}
