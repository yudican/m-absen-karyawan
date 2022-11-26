<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('dashboard')}}">
                            <span><i class="fas fa-arrow-left mr-3 text-capitalize"></i>data absen</span>
                        </a>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body row">
                    <div class="col-md-6">
                        <x-text-field type="date" name="startDate" label="Tanggal Mulai" />
                    </div>
                    <div class="col-md-6">
                        <x-text-field type="date" name="endDate" label="Tanggal Akhir" />
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" wire:click='setFilter'><i class="fa fa-calendar pr-2"></i>Terapkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <livewire:table.data-absen-table params="{{$route_name}}" />
        </div>

        {{-- Modal form --}}
        <div id="form-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog" permission="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize" id="my-modal-title">{{$update_mode ? 'Update' : 'Tambah'}} data absen</h5>
                    </div>
                    <div class="modal-body">
                        <x-text-field type="text" name="user_id" label="User Id" />
                        <x-text-field type="text" name="jadwal_absen_id" label="Jadwal Absen Id" />
                        <x-text-field type="text" name="waktu_absen" label="Waktu Absen" />
                        <x-text-field type="text" name="foto_absen" label="Foto Absen" />
                        <x-text-field type="text" name="status_absen" label="Status Absen" />
                        <x-text-field type="text" name="status_perizinan" label="Status Perizinan" />
                    </div>
                    <div class="modal-footer">

                        <button type="button" wire:click={{$update_mode ? 'update' : 'store' }} class="btn btn-primary btn-sm"><i class="fa fa-check pr-2"></i>Simpan</button>

                        <button class="btn btn-danger btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Batal</a>

                    </div>
                </div>
            </div>
        </div>


        {{-- Modal confirm --}}
        <div id="confirm-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog" permission="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Konfirmasi Hapus</h5>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin hapus data ini.?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" wire:click='delete' class="btn btn-danger btn-sm"><i class="fa fa-check pr-2"></i>Ya, Hapus</button>
                        <button class="btn btn-primary btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')



    <script>
        document.addEventListener('livewire:load', function(e) {
             window.livewire.on('loadForm', (data) => {
                
                
            });
            window.livewire.on('showModal', (data) => {
                $('#form-modal').modal('show')
            });

            window.livewire.on('closeModal', (data) => {
                $('#confirm-modal').modal('hide')
                $('#form-modal').modal('hide')
            });
        })
    </script>
    @endpush
</div>