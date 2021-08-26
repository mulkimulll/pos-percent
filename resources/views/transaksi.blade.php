@extends('layout.index')
@section('content')
<div class="content">
    <div class="d-flex justify-content-center row">
        <div class="container-fluid">
            <div class="container">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama Customer <br> (tgl.beli)</th>
                            <th>Produk</th>
                            <th>Total beli</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi as $item)                            
                        <tr>
                            <td>
                                {{$item->nama_customer}} <br>
                                <small>{{$item->created_at}}</small>
                            </td>
                            <td>{{$item->nama}}</td>
                            <td>Rp. {{$item->total}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nama customer</th>
                            <th>Produk</th>
                            <th>Total beli</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    } );
</script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
@endsection