<div class="page-inner">
    <div class="row">
        @if ($role === 'member')
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="flaticon-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Cuti</p>
                                <h4 class="card-title">{{$cuti}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="flaticon-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Izin</p>
                                <h4 class="card-title">{{$izin}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="flaticon-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Sisa Cuti</p>
                                <h4 class="card-title">{{$sisa_cuti}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                <i class="flaticon-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Sakit</p>
                                <h4 class="card-title">{{$sakit}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="flaticon-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Karyawan</p>
                                <h4 class="card-title">{{$total_karyawan}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="flaticon-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Karyawan Izin</p>
                                <h4 class="card-title">{{$total_izin}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="flaticon-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Karyawan Cuti</p>
                                <h4 class="card-title">{{$total_cuti}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                <i class="flaticon-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Karyawan Sakit</p>
                                <h4 class="card-title">{{$total_sakit}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif


        {{-- absen --}}
        @if ($role === 'member')
        <div class="col-md-5">
            <div class="card card-stats card-round">
                <div class="card-header">
                    <h4 class="header-title">Absen</h4>
                </div>
                <div class="card-body ">
                    <table class="table table-light">
                        <tbody>
                            @foreach ($jadwal_absens as $item)

                            <tr>
                                <td>{{$item->nama_jadwal}}</td>
                                <td>
                                    @if ($item->hasAbsen($item->id) > 0)
                                    <button class="btn btn-success btn-xs" disabled>Sudah Absen</button>
                                    @else
                                    <button class="btn btn-primary btn-xs" wire:click="showModalWebcam('{{$item->id}}')">Absen</button>
                                    @endif

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- riwayat absen --}}
        <div class="col-md-{{$col}}">
            <div class="card card-stats card-round">
                <div class="card-header">
                    <h4 class="header-title">Riwayat Absen</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-light">
                        <thead>
                            <tr>
                                <td>No.</td>
                                @if (in_array($role, ['superadmin', 'admin']))
                                <td>Nama</td>
                                <td>foto</td>
                                @endif
                                <td>Jenis Absen</td>
                                <td>Waktu Absen</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_absens as $key => $absen)
                            <tr>
                                <td>{{$key+1}}</td>
                                @if (in_array($role, ['superadmin', 'admin']))
                                <td>{{$absen->user->name}}</td>
                                <td><img src="{{$absen->foto_absen}}" alt="foto absen" style="height: 40px;"></td>
                                @endif

                                <td>{{$absen->jadwalAbsen->nama_jadwal}}</td>
                                <td>{{$absen->waktu_absen}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal confirm --}}
    <div id="webcam-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" permission="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Form Absen</h5>
                </div>
                <div class="modal-body text-center">
                    <div id="my_camera" class="mx-auto"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" wire:click='takePhoto' class="btn btn-primary btn-sm"><i class="fa fa-check pr-2"></i>Simpan Absen</button>
                    <button class="btn btn-danger btn-sm" wire:click='cancel'><i class="fa fa-times pr-2"></i>Batal</a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

    <script>
        document.addEventListener('livewire:load', function(e) {
             window.livewire.on('loadForm', (data) => {
                
                
            });
            window.livewire.on('showModalWebcam', (type) => {
                Webcam.set({
                    width: 400,
                    height: 350,
                    image_format: 'png',
                    jpeg_quality: 90
                });
                Webcam.attach( '#my_camera' );
                if (type === 'take') {
                    Webcam.snap( function(data_uri) {
                        @this.call('simpanAbsen', data_uri);
                    });
                }else{
                    $('#webcam-modal').modal('show')
                }
                
            });

            window.livewire.on('closeModal', (data) => {
                $('#webcam-modal').modal('hide')
            });
        })
    </script>
    @endpush
</div>