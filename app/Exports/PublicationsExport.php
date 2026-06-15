<?php

namespace App\Exports;

use App\Exports\Sheets\ArticulosSheet;
use App\Exports\Sheets\LibrosSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PublicationsExport implements WithMultipleSheets
{
    protected $publications;

    public function __construct($publications)
    {
        $this->publications = $publications;
    }

    public function sheets(): array
    {
        return [
            new ArticulosSheet($this->publications),
            new LibrosSheet($this->publications),
        ];
    }
}