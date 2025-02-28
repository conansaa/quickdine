@extends('penjual.layout')

@section('title', 'Dashboard')

@section('judul', 'Dashboard')

@section('konten_admin')

@if (session()->has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


<div class="card bg-white border-0 shadow p-4" style="min-height: 70vh">
    <div class="row justify-content-between mb-3">
        <h5 class="col-12 col-lg-6 fw-bold">Data Pesanan</h5>
        <div class="col-12 col-lg-6 d-flex justify-content-end">
                <form action="/dataakun/search" method="get" class="col-7 me-2">
                    <input class="form-control" type="text" name="cari" value="{{ old('cari') }}"  placeholder="Cari pesanan ..." aria-label="Search">
                </form>

        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col" class="text-center">No</th>
                <th scope="col">Nomor Transaksi</th>
                <th scope="col">Nama Pelanggan</th>
                <th scope="col">Produk</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Catatan</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = 1;
            foreach ($ruko as $rukos){
                if($rukos->id_user == Auth::user()->id){
                    $cek = $rukos->id;
                }
            }
            @endphp
            @foreach ($pesanan as $datas)

            @if ($datas->id_ruko == $cek && $datas->status != 'Selesai')
                <tr>
                    <th scope="col" class="text-center">{{ $no++ }}</th>
                    <td class="col-2">
                        <a type="button" class="fw-bold text-decoration-none" data-bs-toggle="modal" data-bs-target="#detailAkun{{ $datas->id }}">{{ $datas->id_transaksi }}</a>
                    </td>
                    @foreach ($user as $nama_user)
                    @if ($nama_user->id == $datas->id_user)
                    <td scope="col">{{ $nama_user->name }}</td>
                    @endif
                    @endforeach
                    <td scope="col">{{ $datas->produk }}</td>
                    <td scope="col">{{ $datas->jumlah }}</td>
                    <td scope="col">{{ $datas->catatan }}</td>
                    <td>
                        <div class="dropdown">
                            @if ($datas->status == 'Dipesan')
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>{{ $datas->status }}</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/ubahstatus/Dalam Proses/{{ $datas->id }}">Dalam Proses</a></li>
                                <li><a class="dropdown-item" href="/ubahstatus/Pesanan Siap/{{ $datas->id }}">Pesanan Siap</a></li>
                            </ul>
                            @elseif ($datas->status == 'Dalam Proses')
                            <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>{{ $datas->status }}</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/ubahstatus/Dipesan/{{ $datas->id }}">Dipesan</a></li>
                                <li><a class="dropdown-item" href="/ubahstatus/Pesanan Siap/{{ $datas->id }}">Pesanan Siap</a></li>
                            </ul>
                            @elseif ($datas->status == 'Pesanan Siap')
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>{{ $datas->status }}</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/ubahstatus/Dipesan/{{ $datas->id }}">Dipesan</a></li>
                                <li><a class="dropdown-item" href="/ubahstatus/Dalam Proses/{{ $datas->id }}">Dalam Proses</a></li>
                            </ul>
                            @elseif ($datas->status == 'Selesai')
                            <button class="btn btn-success"aria-expanded="false">
                                <span>{{ $datas->status }}</span>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>

                <!-- Modal Catatan -->
                <div class="modal fade" id="detailAkun{{ $datas->id }}" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5 fw-bold" id="staticBackdropLabel">Detail Pesanan</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-5 col-md-4 label fw-bold mb-3">No Transaksi</div>
                                    <div class="col-6">{{ $datas->id_transaksi }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5 col-md-4 label fw-bold mb-3">Nama Pesanan</div>
                                    <div class="col-6">{{ $datas->produk }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5 col-md-4 label fw-bold mb-3">Harga Pesanan</div>
                                    @foreach ($menu as $menus)
                                        @if ($menus->nama_menu == $datas->produk)
                                        <div class="col-6">Rp. {{ number_format($menus->harga_menu,0,',','.') }}</div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-5 col-md-4 label fw-bold mb-3">Jumlah Pesanan</div>
                                    <div class="col-6">{{ $datas->jumlah }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5 col-md-4 label fw-bold mb-3">Catatan Pesanan</div>
                                    <div class="col-6">
                                        @if ($datas->catatan == '')
                                        <span>-</span>
                                        @else
                                        {{ $datas->catatan }}
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5 col-md-4 label fw-bold mb-3">Status Pesanan</div>
                                    <div class="col-6">
                                        @if ($datas->status == 'Pesanan Siap')
                                        <button class="btn btn-primary" aria-expanded="false">
                                            <span>{{ $datas->status }}</span>
                                        </button>
                                        @elseif ($datas->status == 'Dipesan')
                                        <button class="btn btn-secondary" aria-expanded="false">
                                            <span>{{ $datas->status }}</span>
                                        </button>
                                        @elseif ($datas->status == 'Selesai')
                                        <button class="btn btn-success" aria-expanded="false">
                                            <span>{{ $datas->status }}</span>
                                        </button>
                                        @else
                                        <button class="btn btn-warning" aria-expanded="false">
                                            <span>{{ $datas->status }}</span>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5 col-md-4 label fw-bold mb-3">Nama Pembeli</div>
                                    @foreach ($user as $users)
                                        @if ($users->id == $datas->id_user)
                                        <div class="col-6 fw-bold">{{ $users->name }}</div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-5 col-md-4 label fw-bold mb-3">Total Transaksi</div>
                                    @foreach ($transaksi as $tr)
                                        @if ($tr->id == $datas->id_transaksi)
                                        <div class="col-6 fw-bold">Rp. {{ number_format($tr->total_harga,0,',','.') }}</div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-5 col-md-4 label fw-bold mb-3">Catatan Transaksi</div>
                                    @foreach ($transaksi as $tr)
                                        @if ($tr->id == $datas->id_transaksi)
                                        <div class="col-6 fw-bold">
                                            @if ($datas->catatan == '')
                                            <span>-</span>
                                            @else
                                            {{ $tr->catatan }}
                                            @endif
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Batas Modal --}}

                @endif
            @endforeach
        </tbody>
    </table>
</div>

@endsection
