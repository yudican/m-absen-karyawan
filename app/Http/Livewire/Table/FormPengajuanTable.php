<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\FormPengajuan;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use App\Http\Livewire\Table\LivewireDatatable;

class FormPengajuanTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_form_pengajuan';
    public $hide = [];

    public function builder()
    {
        return FormPengajuan::query();
    }

    public function columns()
    {
        $this->hide = HideableColumn::where(['table_name' => $this->table_name, 'user_id' => auth()->user()->id])->pluck('column_name')->toArray();
        return [
            Column::name('id')->label('No.'),
            Column::name('user.name')->label('User Id')->searchable(),
            Column::name('jenis_pengajuan')->label('Jenis Pengajuan')->searchable(),
            Column::name('tgl_mulai')->label('Tgl Mulai')->searchable(),
            Column::name('tgl_berakhir')->label('Tgl Berakhir')->searchable(),
            Column::callback(['lampiran'], function ($image) {
                return view('livewire.components.photo', [
                    'image_url' => asset('storage/' . $image),
                ]);
            })->label(__('Lampiran')),
            Column::name('catatan')->label('Catatan')->searchable(),
            Column::name('catatan_admin')->label('Catatan Admin')->searchable(),
            Column::callback('status', function ($status) {
                if ($status == 0) {
                    return '<span class="badge badge-warning">Menunggu Diverifikasi</span>';
                } else if ($status == 1) {
                    return '<span class="badge badge-success">Disetujui</span>';
                } else if ($status == 2) {
                    return '<span class="badge badge-danger">Ditolak</span>';
                }
            })->label('Status')->searchable(),

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
        $this->emit('getDataFormPengajuanById', $id);
    }

    public function getId($id)
    {
        $this->emit('getFormPengajuanId', $id);
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
