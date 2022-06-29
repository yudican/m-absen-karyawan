<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\DataAbsen;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use App\Http\Livewire\Table\LivewireDatatable;

class DataAbsenTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_data_absen';
    public $hide = [];

    public function builder()
    {
        return DataAbsen::query();
    }

    public function columns()
    {
        $this->hide = HideableColumn::where(['table_name' => $this->table_name, 'user_id' => auth()->user()->id])->pluck('column_name')->toArray();
        return [
            Column::name('id')->label('No.'),
            Column::name('user.name')->label('User Id')->searchable(),
            Column::name('jadwalAbsen.nama_jadwal')->label('Jadwal Absen Id')->searchable(),
            Column::name('waktu_absen')->label('Waktu Absen')->searchable(),
            Column::callback('foto_absen', function ($image) {
                return '<img src="' . $image . '" style="height:30px;"  />';
            })->label('Foto Absen')->searchable(),
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
}
