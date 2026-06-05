<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PublicationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $publications;
    private $rowNumber = 0;

    // Recibimos la colección filtrada desde Livewire
    public function __construct($publications)
    {
        $this->publications = $publications;
    }

    public function collection()
    {
        return $this->publications;
    }

    // Cabeceras en mayúsculas idénticas a tu formato
    public function headings(): array
    {
        return [
            'N°',
            'TÍTULO',
            'REVISTA',
            'ISSN',
            'CATEGORIA REVISTA',
            'AÑO',
            'ÁMBITO',
            'AUTORES',
            'GRUPO DE INVESTIGACIÓN',
            'FILIACIÓN INSTITUCIONAL',
            'PAÍS',
            'ENLACE'
        ];
    }

    // Mapeo exacto de los datos por cada fila
    public function map($publication): array
    {
        $this->rowNumber++;

        // Concatenamos los autores ordenados por su pivot
        $authors = $publication->researchers->sortBy(function ($researcher) {
            return $researcher->pivot->author_order ?? 999;
        })->map(fn ($researcher) => trim(implode(' ', array_filter([
            $researcher->name_1,
            $researcher->name_2,
            $researcher->last_name_1,
            $researcher->last_name_2,
        ]))))->filter()->implode(' - ');

        return [
            $this->rowNumber,
            $publication->title,
            $publication->journal ?? '—', 
            $publication->issn ?? '—',
            $publication->journal_category ?? '—',
            $publication->publication_year ?? '—',
            $publication->scope ?? '—',
            $authors ?: '—',
            $publication->research_group ?? '—',
            $publication->institution ?? 'UFPSO',
            $publication->country ?? 'Colombia',
            $publication->link ?? '—',
        ];
    }

    /**
     * Definición de anchos fijos por columna para evitar que los textos se corten
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // N°
            'B' => 45,  // TÍTULO (Más ancho para títulos largos)
            'C' => 15,  // REVISTA
            'D' => 14,  // ISSN
            'E' => 22,  // CATEGORIA REVISTA
            'F' => 10,  // AÑO
            'G' => 15,  // ÁMBITO
            'H' => 40,  // AUTORES
            'I' => 25,  // GRUPO DE INVESTIGACIÓN
            'J' => 25,  // FILIACIÓN INSTITUCIONAL
            'K' => 15,  // PAÍS
            'L' => 35,  // ENLACE
        ];
    }

    /**
     * Capa estético/estilo: Aquí recreamos exactamente el diseño de tu imagen
     */
    public function styles(Worksheet $sheet)
    {
        // 1. Forzar a que las líneas de cuadrícula de Excel estén visibles
        $sheet->setShowGridLines(true);

        // 2. Aplicar estilos a la fila de cabeceras (Fila 1: de la A a la L)
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'name' => 'Arial',
                'size' => 10,
                'color' => ['argb' => 'FF000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE6E6E6'], // El color gris de fondo de tu plantilla
            ],
        ]);

        // Altura elegante para la fila de títulos
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Calcular dinámicamente el total de filas impresas
        $totalRows = $this->publications->count() + 1;

        if ($totalRows > 1) {
            // 3. Estilo general para todo el cuerpo de datos (Fila 2 en adelante)
            $sheet->getStyle('A2:L' . $totalRows)->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 10,
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true, // Auto-ajuste para que los textos largos bajen de renglón
                ],
            ]);

            // Centrar datos en columnas cortas o códigos
            foreach (['A', 'D', 'E', 'F', 'G', 'J', 'K'] as $col) {
                $sheet->getStyle($col . '2:' . $col . $totalRows)
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            // Alinear a la izquierda textos descriptivos largos
            foreach (['B', 'C', 'H', 'I'] as $col) {
                $sheet->getStyle($col . '2:' . $col . $totalRows)
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            }

            // 4. Formatear la columna de ENLACES (L) en azul y subrayado como un hipervínculo real
            $sheet->getStyle('L2:L' . $totalRows)->applyFromArray([
                'font' => [
                    'color' => ['argb' => 'FF0563C1'],
                    'underline' => true,
                ],
            ]);

            // Darle suficiente altura a las filas de datos para que luzca ordenado como en tu foto
            for ($row = 2; $row <= $totalRows; $row++) {
                $sheet->getRowDimension($row)->setRowHeight(60);
            }
        }

        // 5. Dibujar bordes delgados de color negro en toda la tabla activa
        $sheet->getStyle('A1:L' . $totalRows)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
    }
}