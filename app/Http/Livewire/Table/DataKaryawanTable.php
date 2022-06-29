<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\DataKaryawan;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use App\Http\Livewire\Table\LivewireDatatable;

class DataKaryawanTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_data_karyawan';
    public $hide = [];

    public function builder()
    {
        return DataKaryawan::query();
    }

    public function columns()
    {
        $this->hide = HideableColumn::where(['table_name' => $this->table_name, 'user_id' => auth()->user()->id])->pluck('column_name')->toArray();
        return [
            Column::name('id')->label('No.'),
            Column::name('nik')->label('ID Karyawan')->searchable(),
            Column::name('telepon')->label('Telepon')->searchable(),
            Column::name('alamat')->label('Alamat')->searchable(),
            Column::name('tgl_masuk')->label('Tgl Masuk')->searchable(),
            Column::name('tgl_lahir')->label('Tgl Lahir')->searchable(),
            Column::name('jenis_kelamin')->label('Jenis Kelamin')->searchable(),
            Column::name('jabatan')->label('Jabatan')->searchable(),

            Column::callback(['id'], function ($id) {
                return view('livewire.components.action-button', [
                    'id' => $id,
                    'segment' => $this->params
                ]);
            })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataDataKaryawanById', $id);
    }

    public function getId($id)
    {
        $this->emit('getDataKaryawanId', $id);
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
