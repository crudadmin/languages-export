<?php

namespace CrudAdmin\LanguagesExport\Controllers;

use CrudAdmin\LanguagesExport\Export\LanguagesExportHelper;

class ExportController extends Controller
{
    public function index()
    {
        $exportHelper = new LanguagesExportHelper;
        $exportHelper->build();

        $file = $exportHelper->getExportPath();

        return response()->download($file);
    }
}
