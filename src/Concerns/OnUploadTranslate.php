<?php

namespace CrudAdmin\LanguagesExport\Concerns;

use CrudAdmin\LanguagesExport\Export\LanguagesImportHelper;

trait OnUploadTranslate
{
    public function importDynamicTranslate($slug, $file)
    {
        $import = new LanguagesImportHelper($slug);
        $import->loadFile($file);
        $import->importLocales();
    }
}