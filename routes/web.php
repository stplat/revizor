<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function () {

    // $app = app(\App\Models\UikTiming::class);
    // foreach ($app->get() as $value) {
    //     dd($value);
    // }

    // dd($app->get()->each(function ($item) {
    //     dd($item);
    // }));
    // $box = collect([]);
    // $box->put('type', 'asd');
    // $box->put('asd', 'asd');
    // dd($box);
    /* Главная */
    Route::get('/', 'IndexController@index')->name('index');
    Route::post('/charts', 'IndexController@charts');
    /* Проверки */
    Route::get('/checks/violations', 'CheckController@showViolations')->name('checks.violations');


    Route::get('/checks', 'CheckController@index')->name('checks.index');
    Route::get('/checks/{check}', 'CheckController@show')->name('checks.show');
    Route::post('/checks', 'CheckController@store')->name('checks.store');
    Route::put('/checks/{check_id}', 'CheckController@update')->name('checks.update');
    Route::delete('/checks/{check_id}', 'CheckController@destroy')->name('checks.destroy');
    Route::post('/checks/filter-uiks', 'CheckController@filterUiks')->name('checks.filterUiks');
    Route::post('/checks/filter-violations', 'CheckController@filterViolations')->name('checks.filterViolations');
    Route::post('/checks/upload-uik', 'CheckController@uploadUik')->name('checks.uploadUik');
    Route::post('/checks/review-uik', 'CheckController@reviewUik')->name('checks.reviewUik');
    Route::post('/checks/upload-official-vote', 'CheckController@uploadOfficialVote')->name('checks.uploadOfficialVote');
    Route::post('/checks/compare-turnout', 'CheckController@compareTurnout')->name('checks.compareTurnout');
    Route::post('/checks/upload-intermediate-vote', 'CheckController@uploadIntermediateVote')->name('checks.uploadIntermediateVote');
    Route::post('/checks/compare-intermediate-turnout', 'CheckController@compareIntermediateTurnout')->name('checks.compareIntermediateTurnout');
    Route::post('/checks/counting-uik', 'CheckController@countingUik')->name('checks.countingUik');
    Route::get('/checks/all', 'CheckController@all')->name('all');



    /* События (нарушения) */

    /* Участки (УИКи) */

    /* Пользователи */
    Route::get('/users', 'UserController@index')->name('users.index');
    Route::get('/users/{user}', 'UserController@show')->name('checks.show');
    Route::post('/users', 'UserController@store')->name('checks.store');
    Route::put('/users/{user}', 'UserController@update')->name('checks.update');
    Route::delete('/users', 'UserController@destroy')->name('checks.destroy');

    /* Распознавания */
    Route::get('/recognitions', 'RecognitionController@index')->name('recognitions.index');
    Route::get('/recognitions/report', 'RecognitionController@showReport')->name('recognitions.report.show');
    Route::get('/recognitions/recognition/prev', 'RecognitionController@showPrewRecognition')->name('recognitions.recognition.show.prev');
    Route::put('/recognitions/box', 'RecognitionController@updateBox')->name('recognitions.box.update');
    Route::put('/recognitions/checking', 'RecognitionController@updateCheckingRecognition')->name('recognitions.checking.update');

    /* Модалки событий */
    Route::get('/modals/show', 'ModalController@show')->name('modals.show');
    Route::get('/modals/video', 'ModalController@showVideo')->name('modals.video.show');
    Route::get('/modals/violations/list', 'ModalController@showViolationList')->name('modals.violations.list');
    Route::put('/modals/violations', 'ModalController@updateViolation')->name('modals.violation.update');
    Route::post('/modals/claim', 'ModalController@storeClaim')->name('modals.claim.store');
    Route::post('/modals/claim/upload-response', 'ModalController@uploadClaimResponse')->name('modals.claim.uploadResponse');
    Route::post('/modals/comments', 'ModalController@storeComment')->name('modals.comments.store');
    Route::delete('/modals/claim', 'ModalController@destroyClaim')->name('modals.claim.destroy');

    /* Комментарии к событиям */
    Route::post('/comments', 'ViolationCommentController@store')->name('comments.store');

    /* Таблицы vue-table-2 (экспорт) */
    Route::post('/table/export', 'TableController@export')->name('table-export');
});


Auth::routes([
    'register' => false,
    'confirm' => false,
    'email' => false,
    'request' => false,
    'update' => false,
    'reset' => false,
]);
