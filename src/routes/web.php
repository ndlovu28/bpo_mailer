<?php
use Ndlovu28\BpoMailer\Controllers\BpoController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
	Route::get('bpo/send/', [BpoController::class, 'showSendMail']);
	Route::post('bpo/send/', [BpoController::class, 'sendMail']);
});