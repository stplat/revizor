<?php

namespace App\Services;

use App\Models\Applicant;

class ApplicantService
{

  /**
   * Получаем заявителя
   *
   * @param int $id
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   * @return \Illuminate\Support\Collection
   */
  public function getApplicant($id)
  {
    $applicant = Applicant::with('protocols')->find($id);
    return collect($applicant)->put('protocols', $applicant['protocols']->count());
  }

  /**
   * Получаем заявителей
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   * @return \Illuminate\Support\Collection
   */
  public function getApplicants()
  {
    return Applicant::with('protocols')->orderBy('applicant_id')->get()->map(function ($item) {
      return collect($item)->put('protocols', $item->protocols->count());
    });
  }
}
