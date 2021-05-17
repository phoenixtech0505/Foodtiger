<?php

namespace App\Exports;

use App\Visit;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VisitsExport implements FromArray, WithHeadings
{
    protected $visits;

    public function headings(): array
    {
        return [
            'visit_id',
            'table',
            'area',
            'created',
            'customer_name',
            'customer_email',
            'customer_phone_number',
            'note',
            'by',
        ];
    }

    public function __construct(array $visits)
    {
        $this->visits = $visits;
    }

    public function array(): array
    {
        return $this->visits;
    }
}
