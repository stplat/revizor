<?php

namespace App\Repositories;


use App\Models\ViolationProtocol as Model;

/**
 * Class VideosProcessingRepository
 * @package App\Repositories
 */
class ViolationProtocolRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем ссылки на жалобу и ответ
     * @param int $violationId
     * @return ?Model
     */
    public function findProtocolForCLaim(int $violationId): ?Model
    {
        $columns = [
            'protocol_link',
            'response_link',
            'applicant_id as applicant',
            'decree_id as decree',
        ];

        return $this->startCondition()
            ->select($columns)
            ->where('violation_id', $violationId)
            ->first();
    }
}
