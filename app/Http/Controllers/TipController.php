<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tip\TipShowUik;
use App\Http\Requests\Tip\TipShowViolation;
use App\Http\Requests\Tip\TipShowRecognition;

use App\Services\TipService;

class TipController extends Controller
{
    /**
     * @var TipService
     */
    protected $tipService;

    public function __construct(TipService $tipService)
    {
        $this->tipService = $tipService;
    }

    /**
     * Отображение одного события (нарушения)
     *
     * @param TipShowUik $request
     * @return \Illuminate\Support\Collection
     */
    public function showUik(TipShowUik $request): \Illuminate\Support\Collection
    {
        return $this->tipService->getUik($request->input('uik'));
    }

    /**
     * Отображение одного события (нарушения)
     *
     * @param TipShowViolation $request
     * @return \App\Models\Violation
     */
    public function showViolation(TipShowViolation $request)
    {
        $uik = $request->input('uik');

        return collect([
            'violation' => $this->tipService->getViolation($request->input('violation')),
            'votes' => $uik ? $this->tipService->getOfficialVotes($uik) : null,
        ]);
    }

    /**
     * Отображение одного распознавания
     *
     * @param TipShowRecognition $request
     * @return \App\Models\Violation
     */
    public function showRecognition(TipShowRecognition $request): \Illuminate\Support\Collection
    {
        return $this->tipService->getRecognition($request->input('image'));
    }
}
