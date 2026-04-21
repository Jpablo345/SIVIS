<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('investigadores', 'investigadores')->name('investigadores');

    Route::prefix('tipologia')->name('tipologia.')->group(function () {
        Route::prefix('generacion')->name('generacion.')->group(function () {
            Route::view('articulos-cientificos', 'tipologia.generacion.articulos-cientificos')->name('articulos');
            Route::view('libros-investigacion', 'tipologia.generacion.libros-investigacion')->name('libros');
            Route::view('capitulos-libro', 'tipologia.generacion.capitulos-libro')->name('capitulos');
            Route::view('trabajos-eventos', 'tipologia.generacion.trabajos-eventos')->name('trabajos');
            Route::view('otras-publicaciones', 'tipologia.generacion.otras-publicaciones')->name('otras');
        });

        Route::view('desarrollo-tecnologico', 'tipologia.desarrollo-tecnologico')->name('desarrollo');
        Route::view('apropiacion-social', 'tipologia.apropiacion-social')->name('apropiacion');
        Route::view('divulgacion-publica', 'tipologia.divulgacion-publica')->name('divulgacion');
        Route::view('formacion-recurso-humano', 'tipologia.formacion-recurso-humano')->name('formacion');
    });

    Route::prefix('estructura')->name('estructura.')->group(function () {
        Route::view('grupos-investigacion', 'estructura.grupos-investigacion')->name('grupos');
        Route::view('instituciones', 'estructura.instituciones')->name('instituciones');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
