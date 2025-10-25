<?php

use App\Http\Controllers\EvaluationExportController;
use App\Livewire\Auth\Login;
use App\Livewire\Config\Assignments;
use App\Livewire\Config\Chains;
use App\Livewire\Config\EvaluationFields;
use App\Livewire\Config\Notifications;
use App\Livewire\Config\Permissions;
use App\Livewire\Config\Products;
use App\Livewire\Config\Stores;
use App\Livewire\Config\Users;
use App\Livewire\Config\Zones;
use App\Livewire\Dashboard\Home as DashboardHome;
use App\Livewire\Evaluations\Create as EvaluationCreate;
use App\Livewire\Evaluations\Index as EvaluationIndex;
use App\Livewire\Reports\Dashboard as ReportsDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/dashboard')->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::redirect('/home', '/dashboard')->name('home');
    Route::get('/dashboard', DashboardHome::class)->name('dashboard');

    Route::prefix('evaluaciones')->name('evaluations.')->group(function () {
        Route::get('/', EvaluationIndex::class)->name('index')->middleware('role:admin,supervisor,promotor');
        Route::get('/nueva', EvaluationCreate::class)->name('create')->middleware('can:evaluate');
        Route::get('/export/{format}', EvaluationExportController::class)->name('export')->middleware('can:view-reports');
    });

    Route::get('/reportes', ReportsDashboard::class)->name('reports.dashboard')->middleware('can:view-reports');

    Route::prefix('configuracion')->name('config.')->middleware('role:admin,supervisor')->group(function () {
        Route::get('/usuarios', Users::class)->name('users');
        Route::get('/cadenas', Chains::class)->name('chains');
        Route::get('/zonas', Zones::class)->name('zones');
        Route::get('/tiendas', Stores::class)->name('stores');
        Route::get('/asignaciones', Assignments::class)->name('assignments');
        Route::get('/productos', Products::class)->name('products');
        Route::get('/campos', EvaluationFields::class)->name('fields');
        Route::get('/permisos', Permissions::class)->name('permissions');
        Route::get('/notificaciones', Notifications::class)->name('notifications');
    });
});
