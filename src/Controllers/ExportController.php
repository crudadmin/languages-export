<?php

namespace CrudAdmin\LanguagesExport\Controllers;

use CrudAdmin\LanguagesExport\Export\LanguagesExportHelper;

class ExportController extends Controller
{
    public function index($slug = null)
    {
        $exportHelper = new LanguagesExportHelper($slug);
        $exportHelper->build();

        $file = $exportHelper->getExportPath();

        return response()->download($file);
    }
}
