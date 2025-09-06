<?php

namespace App\Exports;

use App\Models\Admin\Daywisemenu_model;
use App\Models\Menu;
use Maatwebsite\Excel\Concerns\FromCollection;

class MenuExport implements FromCollection
{
    public function collection()
    {
        return Daywisemenu_model::all();
    }
}
