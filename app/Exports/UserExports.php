<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class UserExports implements FromView
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
        return view('exports.subscribers', [
            'data' => $this->data
        ]);
    }
}
