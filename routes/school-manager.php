<?php

use App\Livewire\SchoolManager\Auth\Index as SmAuthIndex;
use App\Livewire\SchoolManager\Dashboard as SmDashboard;
use App\Livewire\SchoolManager\StudentList as SmStudentList;
use App\Livewire\SchoolManager\StudentDetail as SmStudentDetail;
use Illuminate\Support\Facades\Route;

Route::name('school-manager.')->group(function () {

    Route::get('/sign-in', SmAuthIndex::class)->name('sign-in')->middleware('guest:school-manager');

    Route::middleware('auth:school-manager')->group(function () {
        Route::get('/logout', [SmAuthIndex::class, 'logout'])->name('logout');
        Route::get('/dashboard', SmDashboard::class)->name('dashboard');
        Route::get('/students', SmStudentList::class)->name('students');
        Route::get('/students/{student}', SmStudentDetail::class)->name('student.detail');
    });
});
