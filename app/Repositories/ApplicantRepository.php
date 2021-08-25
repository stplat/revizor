<?php

namespace App\Repositories;

use App\Models\Applicant as Model;


/**
 * Class ApplicantRepository
 * @package App\Repositories
 */
class ApplicantRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем список по заявителям
     * @param
     */
    public function findApplicantListForModal()
    {
        $columns = [
            'applicant_id as id',
            'name',
            'region_number',
            'commission_name as commission',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('applicant_types', 'violation_applicant_name.applicant_type_id', '=', 'applicant_types.applicant_type_id')
            ->get();
    }
}
