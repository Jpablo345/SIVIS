<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LibrosSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $publications;
    private int $rowNumber = 0;

    public function __construct($publications)
    {
        // Solo libros
        $this->publications = $publications->filter(fn($p) => $p->book !== null)->values();
    }

    public function title(): string
    {
        return 'Libros de Investigación';
    }

    public function collection()
    {
        return $this->publications;
    }

    public function headings(): array
    {
        return [
            'N°',
            'TÍTULO',
            'ISBN',
            'TIPO DE LIBRO',
            'EDITORIAL',
            'MEDIO DE DIFUSIÓN',
            'AÑO',
            'ÁMBITO',
            'AUTORES',
            'GRUPO DE INVESTIGACIÓN',
            'FILIACIÓN INSTITUCIONAL',
            'PAÍS',
            'ENLACE',
        ];
    }

    public function map($publication): array
    {
        $this->rowNumber++;

        $authors = $publication->researchers
            ->sortBy(fn($r) => $r->pivot->author_order ?? 999)
            ->map(fn($r) => trim(implode(' ', array_filter([
                $r->name_1, $r->name_2, $r->last_name_1, $r->last_name_2,
            ]))))
            ->filter()
            ->implode(' - ');

        $groups = $publication->researchers
            ->pluck('researchGroup.group_name')
            ->filter()->unique()->implode(', ');

        $institutions = $publication->researchers
            ->pluck('researchGroup.institution.institution_name')
            ->filter()->unique()->implode(', ');

        return [
            $this->rowNumber,
            $publication->title,
            $publication->book->book_isbn ?? '—',
            $publication->book->bookType?->type_name ?? '—',
            $publication->book->editorial ?? '—',
            $publication->book->means_of_dissemination ?? '—',
            $publication->publication_year ?? '—',
            $publication->scope ?? '—',
            $authors ?: '—',
            $groups ?: '—',
            $institutions ?: '—',
            $publication->country_publication ?? '—',
            $publication->url ?? '—',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 45,
            'C' => 16,
            'D' => 20,
            'E' => 22,
            'F' => 20,
            'G' => 8,
            'H' => 15,
            'I' => 40,
            'J' => 28,
            'K' => 28,
            'L' => 15,
            'M' => 35,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol   = 'M';
        $totalRows = $this->publications->count() + 1;

        // Cabecera
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'name' => 'Arial',
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE6E6E6'],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        if ($totalRows > 1) {
            $sheet->getStyle("A2:{$lastCol}{$totalRows}")->applyFromArray([
                'font'      => ['name' => 'Arial', 'size' => 10],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            ]);

            foreach (['A', 'C', 'D', 'F', 'G', 'H'] as $col) {
                $sheet->getStyle("{$col}2:{$col}{$totalRows}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            // Enlace en azul
            $sheet->getStyle("M2:M{$totalRows}")->applyFromArray([
                'font' => ['color' => ['argb' => 'FF0563C1'], 'underline' => true],
            ]);

            for ($row = 2; $row <= $totalRows; $row++) {
                $sheet->getRowDimension($row)->setRowHeight(55);
            }
        }

        // Bordes
        $sheet->getStyle("A1:{$lastCol}{$totalRows}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $sheet->setShowGridLines(true);
    }
}