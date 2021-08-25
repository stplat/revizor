<?php

namespace App\Http\Controllers;

use App\Services\ApplicantService;

use App\Http\Requests\Applicant\ApplicantStore;
use App\Http\Requests\Applicant\ApplicantShow;
use App\Http\Requests\Applicant\ApplicantUpdate;
use App\Http\Requests\Applicant\ApplicantDestroy;
use App\Models\Applicant;

class ApplicantController extends Controller
{
  protected $applicantService;

  public function __construct(ApplicantService $applicantService)
  {
    $this->applicantService = $applicantService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
//      dd($this->applicantService->getApplicants()->toArray());
    return view('applicants')->with([
      'applicants' => $this->applicantService->getApplicants()
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \App\Http\Requests\Applicant\ApplicantStore
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function store(ApplicantStore $request)
  {
    Applicant::insert([
      'name' => $request->input('name'),
      'is_organisation' => $request->input('organization'),
    ]);

    return $this->applicantService->getApplicants();
  }

  /**
   * Display the specified resource.
   *
   * @param \App\Http\Requests\Applicant\ApplicantShow
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function show(ApplicantShow $request)
  {
    return $this->applicantService->getApplicant($request->input('applicant'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \App\Http\Requests\Applicant\ApplicantUpdate
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function update(ApplicantUpdate $request)
  {
    Applicant::find($request->input('applicant'))->update([
      'name' => $request->input('name'),
      'is_organisation' => $request->input('organization'),
    ]);

    return $this->applicantService->getApplicants();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Http\Requests\Applicant\ApplicantDestroy
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function destroy(ApplicantDestroy $request)
  {
    Applicant::destroy($request->input('applicant'));
    return $this->applicantService->getApplicants();
  }
}
