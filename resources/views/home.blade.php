@extends('layout.index')
@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-7">
                    <h1 class="m-0"> MENU</h1>
                </div>
                <div class="col-sm-5">
                    <form action="{{ route('home') }}" method="GET" class="form-inline ml-0 ml-sm-3">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" name="cari"
                                placeholder="cari menu" aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar btn-warning" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>

                    </form>
                    <button class="btn btn-navbar btn-danger" type="button">
                        <a href="{{ route('home') }}"><i class="fas fa-trash"></i></a>
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if (Session::has('kosong'))
                        <div class="alert alert-warning" role="alert">
                            <i class="far fa-bell"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    @if ($message = Session::get('message'))
                        <div class="alert alert-success" role="alert">
                            <i class="far fa-bell"></i> {{ $message }}
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    @if (Session::has('messageupdated'))
                        <div class="alert alert-danger" role="alert">
                            <i class="far fa-bell"></i>
                        </div>
                    @endif
                </div>
                @if ($produk->isNotEmpty())
                    @foreach ($produk as $item)
                        <div class="col-md-6">
                            <div class="card card-body">
                                <div
                                    class="media align-items-center align-items-lg-start text-center text-lg-left flex-column flex-lg-row">
                                    <div class="mr-2 mb-3 mb-lg-0"> <img src="{{ $item->gambar }}" width="150"
                                            height="150" alt="">
                                    </div>
                                    <div class="media-body">
                                        <h6 class="media-title font-weight-semibold"> <a href="#"
                                                data-abc="true">{{ $item->nama }}</a> </h6>
                                        <p class="mb-3">{{ $item->deskripsi }} </p>
                                    </div>
                                    <div class="mt-3 mt-lg-0 ml-lg-3 text-center">
                                        <h3 class="mb-0 font-weight-semibold">@currency($item->harga)</h3>
                                        <form action="{{ url('/add-to-cart') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_produk" value="{{ $item->id }}">
                                            <input type="hidden" name="harga" value="{{ $item->harga }}">
                                            <button class="btn btn-sm btn-primary mt-4 text-white"><i
                                                    class="fas fa-cart-plus"></i> beli</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div>
                        <h2 class="text-center">Tidak ada data</h2>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
