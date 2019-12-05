<?php

namespace CrudAdmin\LanguagesExport\Admin\Buttons;

use Gogol\Admin\Helpers\Button;
use Gogol\Admin\Models\Model as AdminModel;

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
        $url = action('\CrudAdmin\LanguagesExport\Controllers\ExportController@index');

        return $this->message('Stiahnuť export jazyka môžete na tejto adrese:<br><a target="_blank" href="'.$url.'">'.$url.'</a>');
    }
}