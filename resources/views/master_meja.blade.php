@extends('layout.index')
@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
  .modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
  }

  .modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
  }

  .close {
    color: #888;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
  }

  .close:hover {
    color: #000;
  }
  @media print {
  .noPrint{
    display:none;
  }
}
</style>
@endsection
@section('content')
<div class="content-header">
  <div class="container">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"> Master Meja</h1>
      </div>
      <div class="col-sm-6 text-right">
        <button class="btn btn-sm btn-info" id="openModal">+ Tambah Meja</button>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Table Meja</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <button onclick="window.print();" class="noPrint"> print</button>
            <table id="example1" class="table table-bordered table-striped">
              <thead class="noPrint">
                <tr>
                  <th>no</th>
                  <th>meja</th>
                  <th>qr</th>
                  <th class="text-right">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($meja as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->nama }}</td>
                  <td>{!! QrCode::size(100)->generate(env('APP_URL').'/pesan/no_meja/'.$item->id); !!}</td>
                  <td>
                    <a href="{{ route('hapus.meja', $item->id) }}" class="btn btn-danger"><i
                        class="fa fa-trash"></i></a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>
</div>

<div id="modal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div class="container">
      <h2>Tambah Meja</h2>
      <form action="{{ route('master.meja') }}" method="post">
        @csrf
        <div class="form-group">
          <label>Nama meja</label>
          <input type="text" class="form-control" name="nama">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
      </form>
    </div>
  </div>
</div>
@endsection
@section('js')
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
  $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": true
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
        var openModalButton = document.getElementById("openModal");
        var modal = document.getElementById("modal");
        var closeButton = document.getElementsByClassName("close")[0];

        openModalButton.addEventListener("click", function() {
          modal.style.display = "block";
        });

        closeButton.addEventListener("click", function() {
          modal.style.display = "none";
        });

        window.addEventListener("click", function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        });
</script>
@endsection