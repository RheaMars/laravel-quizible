<?php

use App\Livewire\Register;
use Illuminate\Support\Facades\Route;

Route::get( 'register/{token}', Register::class )->name( 'register' );
