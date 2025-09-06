<?php

namespace App\Imports;

use App\Models\Admin\Daywisemenu_model;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class MenuImport implements ToModel, WithStartRow, WithDrawings
{
    private $rowCount = 2; // since we skip header
    private $drawings = [];

    // Start reading after header row
    public function startRow(): int
    {
        return 2;
    }

    // Collect all drawings (images) from Excel
    public function drawings()
    {
        return $this->drawings;
    }

    public function model(array $row)
    {
        $imagePath = null;

        // Check if image exists for this row
        if (isset($this->drawings[$this->rowCount])) {
            /** @var Drawing $drawing */
            $drawing = $this->drawings[$this->rowCount];
            $extension = $drawing->getExtension() ?: 'png';

            $imageName = uniqid('menu_') . '.' . $extension;
            $storagePath = 'uploads/menus/' . $imageName;

            // Save to storage/app/public/uploads/menus
            Storage::disk('public')->put($storagePath, file_get_contents($drawing->getPath()));

            $imagePath = 'storage/' . $storagePath;
        }

        $menu = new Daywisemenu_model([
            'title'     => $row[0],
            'price'     => $row[1],
            'menu_date' => $row[2],
            'items'     => $row[3],
            'image_url' => $imagePath,
        ]);

        $this->rowCount++;
        return $menu;
    }
}
