<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class PendingSettlements implements FromView
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'Email'
        ];
    }

    public function view(): View
    {
        return view('exports.settlements', [
            'data' => $this->data
        ]);
    }
}
