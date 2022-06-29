<?php

use App\Http\Controllers\AuthController;
use App\Http\Livewire\CrudGenerator;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Settings\Menu;
use App\Http\Livewire\UserManagement\Permission;
use App\Http\Livewire\UserManagement\PermissionRole;
use App\Http\Livewire\UserManagement\Role;
use App\Http\Livewire\UserManagement\User;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Absensi\DataKaryawanController;
use App\Http\Livewire\Absensi\JadwalAbsenController;
use App\Http\Livewire\Absensi\DataAbsenController;
use App\Http\Livewire\Absensi\FormPengajuanController;
// [route_import_path]

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

Route::get('/', function () {
    return redirect('login');
});


Route::post('login', [AuthController::class, 'login'])->name('admin.login');
Route::get('login-member', [AuthController::class, 'loginMemberIndex'])->name('member.login.index');
Route::post('login-member', [AuthController::class, 'loginMember'])->name('member.login');
Route::group(['middleware' => ['auth:sanctum', 'verified', 'user.authorization']], function () {
    // Crud Generator Route
    Route::get('/crud-generator', CrudGenerator::class)->name('crud.generator');

    // user management
    Route::get('/permission', Permission::class)->name('permission');
    Route::get('/permission-role/{role_id}', PermissionRole::class)->name('permission.role');
    Route::get('/role', Role::class)->name('role');
    Route::get('/user', User::class)->name('user');
    Route::get('/menu', Menu::class)->name('menu');

    // App Route
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Master data

    Route::get('/data-karyawan', DataKaryawanController::class)->name('data-karyawan');
    Route::get('/jadwal-absen', JadwalAbsenController::class)->name('jadwal-absen');
    Route::get('/data-absen', DataAbsenController::class)->name('data-absen');
    Route::get('/form-pengajuan', FormPengajuanController::class)->name('form-pengajuan');
    // [route_path]

});
