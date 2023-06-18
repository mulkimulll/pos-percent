@extends('layout.index')
@section('content')
<div class="content-header">
  <div class="container">
    <div class="row mb-2">
      <div class="col-sm-6">Ubah produk {{ $produk->nama }}</h1>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <form action="{{ route('ubah.produk', $produk->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label>Nama produk</label>
                <input type="text" class="form-control" name="nama" value="{{ $produk->nama }}">
              </div>
              <div class="form-group">
                <label>Harga</label>
                <input type="number" class="form-control" name="harga" value="{{ $produk->harga }}">
              </div>
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="6">{{ $produk->deskripsi }}</textarea>
              </div>
              <img src="{{ asset($produk->gambar) }}" alt="" height="100px">
              <div class="form-group">
                <label for="exampleInputFile">Gambar</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" name="gambar" id="customFile">
                  <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
@if(session('success'))
<script>
    Swal.fire({
        title: 'Success',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif
@endsection