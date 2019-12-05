<?php

Route::group([ 'namespace' => 'CrudAdmin\LanguagesExport\Controllers', 'middleware' => ['web', 'admin'] ], function(){
    Route::get('/admin/languages/export', 'ExportController@index');
});