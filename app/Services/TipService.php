<?php

namespace App\Services;

use App\Models\Constant;
use App\Repositories\UikRepository;
use App\Repositories\ViolationRepository;
use App\Repositories\BoxRecognitionRepository;
use App\Repositories\ConstantRepository;
use App\Repositories\VoteOfficialRepository;

class TipService
{
    /**
     * @var UikRepository
     */
    protected $uikRepository;

    /**
     * @var ViolationRepository
     */
    protected $violationRepository;

    /**
     * @var BoxRecognitionRepository
     */
    protected $boxRecognitionRepository;

    /**
     * @var VoteOfficialRepository
     */
    protected $voteOfficialRepository;

    /**
     * @var ConstantRepository
     */
    protected $constantRepository;

    /**
     * UikService constructor.
     */
    public function __construct()
    {
        $this->uikRepository = app(UikRepository::class);
        $this->violationRepository = app(ViolationRepository::class);
        $this->boxRecognitionRepository = app(BoxRecognitionRepository::class);
        $this->voteOfficialRepository = app(VoteOfficialRepository::class);
        $this->constantRepository = app(ConstantRepository::class);
    }

    /**
     * Получаем участок (УИК) по id
     * @param int $uikId
     * @return \Illuminate\Support\Collection
     */
    public function getUik(int $uikId): \Illuminate\Support\Collection
    {
        return $this->uikRepository->findUikForTip($uikId);
    }

    /**
     * Получаем нарушение (событие) по id
     * @param int $violationId
     * @return \Illuminate\Support\Collection
     */
    public function getViolation(int $violationId)
    {
        return collect($this->violationRepository->findViolationForTip($violationId))->put('constant', Constant::first());
    }

    /**
     * Получаем данные голосованию
     * @param int $uikId
     * @return \Illuminate\Support\Collection
     */
    public function getOfficialVotes(int $uikId)
    {
        return $this->voteOfficialRepository->findOfficialVoteForTip($uikId);
    }

    /**
     * Получаем распознавание по violation_id
     * @param int $imageId
     * @return \App\Models\Violation
     */
    public function getRecognition(int $imageId): \Illuminate\Support\Collection
    {
        return $this->boxRecognitionRepository->findRecognitionForTip($imageId);
    }
}
