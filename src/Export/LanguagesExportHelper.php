<?php

namespace CrudAdmin\LanguagesExport\Export;

use Gogol\Admin\Helpers\Gettext;
use Gettext\Translations;
use Localization;
use Admin;

class LanguagesExportHelper
{
    protected $slug;

    public function __construct($slug)
    {
        $this->slug = $slug;
    }

    public function build()
    {
        $this->generatePoFile();
    }

    public function getExportPath()
    {
        return storage_path('app/export.po');
    }

    public function generatePoFile()
    {
        $localeTranslations = $this->getLocaleExports();

        $translations = $this->createTranslationsStack();

        $translations->mergeWith($localeTranslations);

        $translations->toPoFile($this->getExportPath());
    }

    public function createTranslationsStack()
    {
        $translations = new Translations();

        $locale = (new Gettext)->getLocale($this->slug);

        $translations->setLanguage($locale);
        $translations->setHeader('Project-Id-Version', 'Translations export powered by CrudAdmin.com');

        return $translations;
    }

    public function getLocaleFields($model)
    {
        $fields = $model->getFields();

        $columns = [];

        foreach ($fields as $key => $field) {
            if ( $model->hasFieldParam($key, 'locale') )
                $columns[] = $key;
        }

        return $columns;
    }

    public function getLocaleExports()
    {
        $models = Admin::getAdminModels();

        $translations = new Translations();

        foreach ($models as $model)
        {
            $fields = $this->getLocaleFields($model);

            //Skip unwanted tables
            if ( in_array($model->getTable(), ['languages']) || count($fields) == 0 )
                continue;

            $rows = $model->withoutGlobalScopes()
                          ->select(array_merge([$model->getKeyName()], $fields))
                          ->whereNull('deleted_at')
                          ->get();

            foreach ($rows as $row) {
                foreach ($fields as $field) {
                    $translation = $translations->insert(null, $row->{$field});
                    $translation->addComment(json_encode([
                        'table' => $model->getTable(),
                        'id' => $row->getKey(),
                        'column' => $field,
                    ]));

                    if ( $translate = @$row->getAttribute($field)[$this->slug] ) {
                        $translation->setTranslation($translate);
                    }
                }
            }
        }
        return $translations;
    }
}