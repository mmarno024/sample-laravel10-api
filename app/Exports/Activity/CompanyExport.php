<?php

namespace App\Exports\Activity;

use App\Models\Activity\Company;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompanyExport implements FromArray, WithCustomStartCell, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $company = Company::with('rel_citid')->with('rel_disid')->get();
        $datas = [];
        foreach ($company as $k => $v) {
            $datas[$k]['compname'] = $v->compname;
            $datas[$k]['address'] = $v->address;
            $datas[$k]['citname'] = $v->rel_citid->citname;
            $datas[$k]['disname'] = $v->rel_disid->disname;
            $datas[$k]['post_code'] = $v->post_code;
            $datas[$k]['phone'] = $v->phone;
            $datas[$k]['email'] = $v->email;
            $datas[$k]['website'] = $v->website;
            $datas[$k]['director'] = $v->director;
            $datas[$k]['status'] = $v->status;
        }
        return $datas;
    }

    public function startCell(): string
    {
        return 'B2';
    }

    public function headings(): array
    {
        return [
            'Nama Perusahaan',
            'Alamat Lengkap',
            'Kota/Kabupaten',
            'Kecamatan',
            'Kode Pos',
            'Telepon',
            'Email',
            'Website',
            'Direktur',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            2    => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['bold' => true]],

            // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 10,
            'C' => 50,
            'D' => 50,
            'E' => 50,
            'F' => 50,
            'G' => 50,
            'H' => 50,
            'I' => 50,
            'J' => 50,
            'K' => 50,
        ];
    }
}
