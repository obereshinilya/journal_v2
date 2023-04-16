<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HourExport implements FromView, WithStyles, ShouldAutoSize
{

    private $title, $data, $start_hour;

    public function __construct($title, $data, $start_hour)
    {
        $this->title = $title;
        $this->data = $data;
        $this->start_hour = $start_hour;
    }

    public function view(): View
    {
        return view('web.excel.hour_params', [
            'data' => $this->data,
            'title' => $this->title,
            'start_hour' => $this->start_hour,
        ]);
    }

    public function styles(Worksheet $sheet)
    {

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],

        ];
        $styleArray2 = [
            'font' => [
                'size' => 15
            ]
        ];
        $alphabet = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'AA', 'AB', 'AC'
        ];
        $diap = 'A1:' . mb_strtoupper($alphabet[26]) . (count($this->data)+2);
        $sheet->getStyle('A1')->applyFromArray($styleArray2);
        $sheet->getStyle($diap)->applyFromArray($styleArray);
        $sheet->getRowDimension('1')->setRowHeight(20);
    }

}
