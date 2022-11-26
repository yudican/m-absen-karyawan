<?php

namespace App\Http\Livewire\Table;

use App\Exports\ExportDataAbsen;
use App\Models\HideableColumn;
use App\Models\DataAbsen;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use App\Http\Livewire\Table\LivewireDatatable;
use Maatwebsite\Excel\Facades\Excel;

class DataAbsenTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable', 'setFilter'];
    public $hideable = 'select';
    public $table_name = 'tbl_data_absen';
    public $hide = [];
    public $exportable = true;
    public $dateFilter;

    public function builder()
    {
        if (isset($this->dateFilter) && is_array($this->dateFilter)) {
            return DataAbsen::query()->whereBetween('waktu_absen', $this->dateFilter)->orderBy('waktu_absen', 'asc');
        }
        return DataAbsen::query()->orderBy('waktu_absen', 'asc');
    }

    public function columns()
    {
        $this->hide = HideableColumn::where(['table_name' => $this->table_name, 'user_id' => auth()->user()->id])->pluck('column_name')->toArray();
        return [
            Column::name('id')->label('No.'),
            Column::name('user.username')->label('NIK Karyawan')->searchable(),
            Column::name('user.name')->label('Nama Karyawan')->searchable(),
            Column::name('jadwalAbsen.nama_jadwal')->label('Jenis Absen')->searchable(),
            Column::name('waktu_absen')->label('Waktu Absen')->searchable(),
            // Column::callback('foto_absen', function ($image) {
            //     return '<img src="' . $image . '" style="height:30px;"  />';
            // })->label('Foto Absen')->searchable(),
            // Column::name('status_absen')->label('Status Absen')->searchable(),
            // Column::name('status_perizinan')->label('Status Perizinan')->searchable(),

            // Column::callback(['id'], function ($id) {
            //     return view('livewire.components.action-button', [
            //         'id' => $id,
            //         'segment' => $this->params
            //     ]);
            // })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataDataAbsenById', $id);
    }

    public function getId($id)
    {
        $this->emit('getDataAbsenId', $id);
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }

    public function toggle($index)
    {
        if ($this->sort == $index) {
            $this->initialiseSort();
        }

        $column = HideableColumn::where([
            'table_name' => $this->table_name,
            'column_name' => $this->columns[$index]['name'],
            'index' => $index,
            'user_id' => auth()->user()->id
        ])->first();

        if (!$this->columns[$index]['hidden']) {
            unset($this->activeSelectFilters[$index]);
        }

        $this->columns[$index]['hidden'] = !$this->columns[$index]['hidden'];

        if (!$column) {
            HideableColumn::updateOrCreate([
                'table_name' => $this->table_name,
                'column_name' => $this->columns[$index]['name'],
                'index' => $index,
                'user_id' => auth()->user()->id
            ]);
        } else {
            $column->delete();
        }
    }

    public function export()
    {
        return Excel::download(new ExportDataAbsen(), 'data-absen.xlsx');
    }

    public function setFilter($date)
    {
        $this->dateFilter = $date;
    }
}
