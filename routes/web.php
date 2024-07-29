<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\AdminPanel\adminController ;
use App\Http\Controllers\SupervisorPanel\supervisorController;
use App\Http\Controllers\superuserPanel\superuserController ;
use App\Http\Controllers\EncoderPanel\encoderController;
use App\Http\Controllers\userManagementController;
use App\Http\Controllers\activityLogsController;
use App\Http\Controllers\cvrecordController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\grantController;
use App\Http\Controllers\bske2023Controller;
use App\Http\Controllers\nle2022Controller;
use App\Http\Controllers\rvManagmentController;
use App\Http\Controllers\rvrecordsController;
use App\Http\Controllers\userlogsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\setdrpdwnController;
use App\Http\Controllers\plhlController;
use App\Http\Controllers\latlongController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|brgy
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'checkUserSession'])->group(function () {
    // Routes that require single-session login protection
    // Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    // Route::get('/profile', [HomeController::class, 'profile'])->name('profile');

});

Route::get('/dashboard', [roleController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth','role:admin'])->controller(adminController::class)->group(function () {
    route::get('dashboard/admin', 'index')->name('dashboard.admin');
    Route::get('/dashboard/admin/profile/edit/{id}', 'profileedit')->name('admin.profile');

    Route::post('/dashboard/getmuncit/', 'getmuncit')->name('dashboard.getmuncit');
    Route::get('/allusers/show', 'userShow')->name('allusers.show');
    Route::post('/allusers/store', 'registerUser')->name('allusers.store');
    Route::get('/allusers//edit/{id}', 'userEdit')->name('allusers.edit');
    Route::get('/allusers/delete/{id}', 'userDelete')->name('allusers.delete');

    Route::get('/allusers/performance', 'performanceView')->name('allusers/performance');
    Route::post('/allusers/performance/fetch-encoder', 'fetchEncoder')->name('allusers/performance/fetch-encoder');
    Route::post('/allusers/performance/fetch-brgy', 'fetchBrgyEncoder')->name('allusers/performance/fetch-brgy');
    Route::post('/allusers/performance/fetch-monthyear', 'fetchMonthYear')->name('allusers/performance/fetch-monthyear');

});

Route::middleware(['auth','role:superuser'])->controller(superuserController::class)->group(function () {
    route::get('dashboard/superuser', 'index')->name('dashboard.superuser');
});

Route::middleware(['auth','role:admin'])->controller(rvrecordsController::class)->group(function(){
    route::get('/dashboard/recordsadmin', 'recordsadmin')->name('admin.recordsadmin');
    route::post('/dashboard/selmuncit', 'selectmuncit')->name('admin.selectmuncit');
    route::post('/dashboard/selBrgy', 'selectBrgy')->name('admin.selectBrgy');
    route::get('/dashboard/admin/summary', 'adminsumm')->name('dashboard.adminsumm');
});

Route::middleware('auth')->controller(userlogsController::class)->group(function(){
    Route::get('/allusers/userlogs', 'userlogs')->name('allusers.userlogs');
});

Route::middleware('auth')->controller(plhlController::class)->group(function(){
    Route::get('/hlrecords', 'hlindex')->name('hlrecords.index');
    Route::post('/hlrecords/selbrgy', 'selbrgy')->name('hlrecords.selbrgy');
    Route::post('/hlrecords/delete', 'hldelete')->name('hlrecords.hldelete');
    Route::get('/hlrecords/edit/{id}', 'hledit')->name('hlrecords.hledit');
    Route::post('/hlrecords/update', 'hlupdate')->name('hlrecords.hlupdate');
    Route::get('/hlrecords/vmembers', 'vmembers')->name('hlrecords.vmembers');

    Route::get('/pbpcrecords', 'pbpcindex')->name('pbpcrecords.index');
    Route::post('/pbpcrecords/delete', 'pbpcdelete')->name('pbpcrecords.pbpcdelete');
    Route::get('/pbpcrecords/edit/{id}', 'pbpcedit')->name('pbpcrecords.pbpcedit');
    Route::post('/pbpcrecords/update', 'pbpcupdate')->name('pbpcrecords.pbpcupdate');
});


Route::middleware(['auth','role:supervisor'])->controller(supervisorController::class)->group(function () {
    route::get('/dashboard/supervisor', 'index')->name('dashboard.supervisor');
    Route::get('/supervisor/user/show', 'userShow')->name('supervisor.show');
    Route::post('/supervisor/user/store', 'registerUser')->name('supervisor.store');
    Route::get('/supervisor/user/edit/{id}', 'userEdit')->name('supervisor.edit');
    Route::get('/supervisor/user/delete/{id}', 'userDelete')->name('supervisor.delete');
    route::get('dashboard/supervisor/data', 'dataview')->name('dashboard.supervisor.dataview');
    Route::get('/dashboard/supervisor/profile/edit/{id}', 'profileedit')->name('supervisor.profile');
    Route::post('/dashboard/supervisor/profile/update', 'profileupdate')->name('supervisor.updateuser');
    Route::post('/dashboard/supervisor/profile/change-password', 'changePassword')->name('supervisor.changepassword.post');

    Route::get('/supervisor/users/performance', 'performanceView')->name('supervisor.performance');

});

Route::middleware('auth')->controller(encoderController::class)->group(function () {
    route::get('/dashboard/encoder','index')->name('dashboard.encoder');
    Route::post('/dashboard/encoder/fetchbrgy', 'fetchBrgy')->name('encoder.fetchBrgy');
    Route::post('/dashboard/encoder/fetchpbpc', 'fetchpbpc')->name('encoder.fetchpbpc');
    Route::post('/dashboard/encoder/fetchhl', 'fetchhl')->name('encoder.fetchhl');
    Route::post('/dashboard/encoder/vnames', 'vnames')->name('encoder.vnames');
    Route::post('/dashboard/encoder/savepl', 'savePl')->name('encoder.savepl');
    Route::post('/dashboard/encoder/vhlnames', 'vhlnames')->name('encoder.vhlnames');
    Route::get('/dashboard/encoder/getHLid', 'getHLid')->name('encoder.getHLid');
    Route::post('/dashboard/encoder/savehl', 'saveHl')->name('encoder.savehl');
    Route::get('/dashboard/encoder/edit/{id}', 'vedit')->name('encoder.vedit');
    Route::post('/dashboard/encoder/storeorupdate', 'storeorupdate')->name('encoder.storeorupdate');
    Route::get('/dashboard/encoder/vmdelete/{id}', 'vmdelete')->name('encoder.vmdelete');
    Route::post('/dashboard/encoder/store-silda', 'saveSilda')->name('encoder.save-silda');
    Route::get('/dashboard/encoder/profile/edit/{id}', 'profileedit')->name('encoder.profile');
    Route::post('/dashboard/encoder/profile/update', 'profileupdate')->name('encoder.updateuser');
    Route::post('/dashboard/encoder/profile/change-password', 'changePassword')->name('change.password.post');
    Route::get('/dashboard/encoder/profile/view/{id}', 'viewInfo')->name('encoder.view-info');
    Route::get('/dashboard/encoder/profile/view-details/', 'viewDetails')->name('encoder.view-details');
    Route::get('/generate-qrcode/{id}','generateQRCode')->name('generate.qrcode');
});

Route::middleware('auth')->controller(userManagementController::class)->group(function () {
    route::get('/usermanagement', 'index')->name('usermanagement.dashboard');
    Route::post('/usermanagement/store', 'registerUser')->name('usermanagement.store');
    Route::get('/usermanagement/edit/{id}', 'userEdit')->name('usermanagement.edit');
    Route::get('/usermanagement/delete/{id}', 'userDelete')->name('usermanagement.delete');

});

Route::middleware('auth')->controller(activityLogsController::class)->group(function () {
    route::get('/activitylogs', 'index')->name('activitylogs.dashboard');
});

Route::middleware('auth')->controller(grantController::class)->group(function () {
    Route::get('/district/grants', 'index')->name('district.grants');
    Route::post('/district/grants/grants-muncit', 'getHLmuncit')->name('grants.muncit');
    Route::post('/district/grants/grants-brgy', 'getHLbrgy')->name('grants.brgy');
    Route::post('/district/grants/grants-type', 'getgrantType')->name('grants.gtype');
    Route::post('/district/grants/grants-date', 'fltrdate')->name('grant.fltrdate');
    Route::post('/district/grants/grants', 'grantdelete')->name('grant.delete');
    Route::get('/district/grants/grants/edit/{id}', 'grantedit')->name('grant.edit');
    Route::post('/district/grants/grants/update', 'grantupdate')->name('grant.update');
    Route::get('/district/grants/grants-summary', 'grantSumm')->name('grant.grantsummary');

    Route::post('/district/grants/grants-names', 'grantnames')->name('grants.names');
    Route::post('/district/grants/grants-add', 'granttypeadd')->name('grants.addtype');
    Route::post('/district/grants/grants-fetch', 'grantfetch')->name('grants.fetch');
    Route::post('/district/grants/grants-save', 'grantsave')->name('grants.save');

    Route::get('/district/grants/viewrecords', 'viewrecords')->name('grants.viewrecords');
    Route::post('/district/grants/view/delete', 'viewdelete')->name('grants.viewdelete');
});

Route::middleware('auth')->controller(cvrecordController::class)->group(function () {
    Route::get('/cvrecord', 'index')->name('cvrecord.index');
    Route::get('/cvrecord/cvhlsumm', 'cvhlsumm')->name('cvrecord.cvhlsumm');
    Route::post('/cvrecord/muncit', 'cvmuncit')->name('cvrecord.cvmuncit');
    Route::post('/cvrecord/brgy', 'cvrecordbrgy')->name('cvrecord.brgy');
    Route::post('/cvrecord/selHL', 'selHL')->name('cvrecord.selHL');
    Route::post('/cvrecord/sortPurok', 'sortPurok')->name('cvrecord.sortPurok');
});

Route::middleware('auth')->controller(latlongController::class)->group(function () {
    Route::get('/coordinates', 'index')->name('coordinates.index');
    Route::post('/coordinates/getbarangay', 'getbrgy')->name('coordinates.getbrgy');
    Route::post('/coordinates/save', 'coordsave')->name('coordinates.coordsave');
    Route::post('/coordinates/delete', 'coorddelete')->name('coordinates.coorddelete');
    Route::get('/coordinates/edit/{id}', 'coordedit')->name('coordinates.coordedit');
    Route::post('/coordinates/update', 'coordupdate')->name('coordinates.coordupdate');
});



Route::middleware('auth')->controller(bske2023Controller::class)->group(function(){
    route::get('/archives/bske2023', 'index')->name('bske2023.index');
    Route::post('/archives/bske2023/fetch-muncit', 'fetchmuncitss')->name('stamargarita.fetchmuncitss');
    Route::post('/archives/bske2023/fetch-brgy', 'fetchbrgyss')->name('stamargarita.fetchbrgyss');
    Route::post('/archives/bske2023/fetch-position', 'fetchpositionss')->name('stamargarita.fetchpositionss');

    Route::post('/archives/bske2023/update/party', 'updtparty')->name('archives.updtparty');
});

Route::middleware('auth')->controller(nle2022Controller::class)->group(function(){
    route::get('/archives/nle2022', 'index')->name('nle2022.index');
    route::get('/archives/nle2022/statistics', 'statistics2022')->name('nle2022.statistics');
    route::post('/archives/nle2022/statistics/data', 'statistics2022data')->name('nle2022.data');
    Route::post('/archives/nle2022/fetch-muncit', 'fetchmuncitss')->name('nle2022.fetchmuncitss');
    Route::post('/archives/nle2022/fetch-brgy', 'fetchbrgyss')->name('nle2022.fetchbrgyss');
    Route::post('/archives/nle2022/fetch-position', 'fetchpositionss')->name('nle2022.fetchpositionss');
    Route::post('/archives/nle2022/fetch-candidate', 'fetchcandidate')->name('nle2022.fetchcandidate');
    Route::post('/archives/fetch-getposition', 'getposition')->name('archives.getposition');
});

Route::middleware('auth')->controller(rvManagmentController::class)->group(function(){
    route::get('/rv/manage/{munsel}', 'index')->name('rv.manage.index');
});

Route::middleware('auth')->controller(setdrpdwnController::class)->group(function(){
    route::get('/setdrpdwn', 'index')->name('setdrpdwn.index');
});



// Route::middleware('auth')->controller(QRCodeController::class)->group(function () {
//     Route::get('/generate-qrcode/{id}','generateQRCode')->name('generate.qrcode');
// });
// Route::get('/dashboard/encoder/profile/change-password', [App\Http\Controllers\PasswordController::class, 'showChangePasswordForm'])->name('change.password');
// Route::post('/dashboard/encoder/profile/change-password', [App\Http\Controllers\PasswordController::class, 'changePassword'])->name('change.password.post');
