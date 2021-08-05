<?php

namespace CrudAdmin\LanguagesExport\Admin\Buttons;

use Admin\Helpers\Button;
use Admin\Eloquent\AdminModel;

class DownloadLanguageExportButton extends Button
{
    /*
     * Here is your place for binding button properties for each row
     */
    public function __construct(AdminModel $row)
    {
        //Name of button on hover
        $this->name = 'Stiahnuť export';

        //Button classes
        $this->class = 'btn-primary';

        //Button Icon
        $this->icon = 'fa-download';
    }

    /*
     * Firing callback on press button
     */
    public function fire(AdminModel $row)
    {
        $url = action('\CrudAdmin\LanguagesExport\Controllers\ExportController@index', $row->getSlug());

        return $this->redirect($url);
    }
}