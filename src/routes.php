<?php
    Route::prefix('Task')->group(function () {
        Route::get('Run','TaskProcess@Run');
        Route::get('Check','TaskProcess@Check');
        Route::get('LoadJS','TaskProcess@LoadJs');
    });
        
