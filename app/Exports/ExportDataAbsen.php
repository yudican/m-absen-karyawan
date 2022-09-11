<?php

namespace App\Exports;

use App\Models\DataAbsen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

//     user.name
// jadwalAbsen.nama_jadwal
// waktu_absen
class ExportDataAbsen implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $params = [];
    protected $title = null;

    public function __construct($params = [], $title = 'Menu List')
    {
        $this->params = $params;
        $this->title = $title;
    }

    public function query()
    {
        return DataAbsen::query();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->user->username,
            $row->user->name,
            $row->jadwalAbsen->nama_jadwal,
            $row->waktu_absen,
        ];
    }

    public function headings(): array
    {
        return [
            'ID Absen',
            'NIK Karyawan',
            'Nama Karyawan',
            'Jenis Absen',
            'Waktu Absen',
        ];
    }


    // /**
    //  * @return array
    //  */
    // public function sheets(): array
    // {
    //     $sheets = [];

    //     return $sheets;
    // }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }
}
