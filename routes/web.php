<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Dropdowns\Index as DropdownsIndex;
use App\Livewire\HomeIndex;
use App\Livewire\Rules\Index as RulesIndex;
use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Checksheet\MasterIndex; // TAMBAHKAN LAGI
use Illuminate\Support\Facades\Route;
use App\Livewire\Checksheet\ChecksheetDetail;
use App\Livewire\Checksheet\ChecksheetReport;
use App\Livewire\Checksheet\ChecksheetReportDetail;
use App\Livewire\Checksheet\ChecksheetHistory;


Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home.index');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/home', HomeIndex::class)->name('home.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', UsersIndex::class)->name('users.index');
    Route::get('/rules', RulesIndex::class)->name('rules.index');
    Route::get('/dropdowns', DropdownsIndex::class)->name('dropdowns.index');
    Route::get('/checksheet/report', ChecksheetReport::class)->name('checksheet.report');

    Route::get('/checksheet/detail/{id}', ChecksheetDetail::class)->name('checksheet.detail');
    Route::get('/checksheet/report/{id}', ChecksheetReportDetail::class)->name('checksheet.report.detail');
  // Master Checksheet
  Route::get('/checksheet/master', MasterIndex::class)->name('checksheet.master.index');
  Route::get('/checksheet/history', ChecksheetHistory::class)->name('checksheet.history');

});

Route::get('/manifest.json', function () {
    $base = url('/'); // otomatis baca APP_URL dari .env
    return response()->json([
        'name'             => 'QG Checksheet App',
        'short_name'       => 'QG Checksheet',
        'start_url'        => $base,
        'scope'            => $base . '/',
        'display'          => 'standalone',
        'background_color' => '#ffffff',
        'theme_color'      => '#1e293b',
        'orientation'      => 'portrait-primary',
        'icons'            => [
            [
                'src'     => asset('images/favicon/web-app-manifest-192x192.png'),
                'sizes'   => '192x192',
                'type'    => 'image/png',
                'purpose' => 'any maskable',
            ],
            [
                'src'     => asset('images/favicon/web-app-manifest-512x512.png'),
                'sizes'   => '512x512',
                'type'    => 'image/png',
                'purpose' => 'any maskable',
            ],
        ],
    ])->header('Content-Type', 'application/manifest+json');
});


require __DIR__ . '/auth.php';
