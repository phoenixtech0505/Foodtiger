<?php

namespace App\Imports;

use App\Categories;
use App\Items;
use App\Restorant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemsImport implements ToModel, WithHeadingRow
{
    public function __construct(Restorant $restorant)
    {
        $this->restorant = $restorant;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $category = Categories::where(['name' => $row['category'], 'restorant_id' => $this->restorant->id])->first();

        if ($category != null) {
            return new Items([
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'category_id' => $category->id,
                'image' => $row['image'],
            ]);
        } else {
            $category = new Categories;
            $category->name = $row['category'];
            $category->restorant_id = $this->restorant->id;
            $category->save();

            return new Items([
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'category_id' => $category->id,
                'image' => $row['image'],
            ]);
        }
    }
}
