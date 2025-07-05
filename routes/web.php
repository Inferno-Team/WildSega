<?php

use App\Models\Tag;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return ['Laravel' => app()->version()];
});
