<?php

namespace CrudAdmin\LanguagesExport\Export;

use File;
use Gettext\Translations;
use Admin;
use Localization;
use Ajax;

class LanguagesImportHelper
{
    private $slug;

    private $translations;

    private $messages;

    public function __construct($slug)
    {
        $this->slug = $slug;
    }

    public function loadFile($file)
    {
        $this->translations = Translations::fromPoFile($file->basepath);
    }

    public function log($message)
    {
        $this->messages[] = $message;

        Ajax::warning($message);
    }

    public function getColumnValue($json)
    {
        $row = Admin::getModelByTable($json->table)
                        ->withoutGlobalScopes()
                        ->where('id', $json->id)
                        ->select([$json->column])
                        ->whereNull('deleted_at')
                        ->first();

        $defaultLanguage = Localization::getDefaultLanguage()->slug;

        if ( ! $row ) {
            return;
        }

        $value = $row->getAttribute($json->column);

        if ( is_array($value) ) {
            return array_filter($value);
        } else {
            return [
                $defaultLanguage => $value,
            ];
        }
    }

    public function importLocales()
    {
        foreach ($this->translations as $key => $translation) {
            foreach ($translation->getComments() as $comment) {
                //Skip not json format comments
                if (!($json = json_decode($comment))){
                    $this->log('Wrong JSON format - '.$comment);
                    continue;
                }

                //If row has not been found
                if ( ! ($value = $this->getColumnValue($json)) ) {
                    $this->log('Row has not been found - '.$comment);
                    continue;
                }

                $value[$this->slug] = $translation->getTranslation();

                Admin::getModelByTable($json->table)
                     ->withoutGlobalScopes()
                     ->where('id', $json->id)
                     ->update([ $json->column => json_encode($value) ]);
            }
        }
    }
}