@extends('layout.index')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="container mt-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4"> <img class="img"
                                    src="{{asset('img/logo.jpg')}}" /> </div>
                            <div class="col-md-8 text-right">
                                <h4 style="color: #1da0f8;"><strong>Percent Coffee</strong></h4>
                                <p>Jl. Pd. Jaya No.56, Pd. Jaya, Kec. Pd. Aren <br>
                                <p>Kota Tangerang Selatan, Banten 15220 <br>
                                <p><i class="fa fa-instagram" aria-hidden="true"></i> percentcoffee.idn</p>
                            </div>
                        </div> <br />
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2>INVOICE</h2>
                                <h5></h5>
                            </div>
                        </div> <br />
                        <div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            <h5>Deskripsi</h5>
                                        </th>
                                        <th>
                                            <h5>Jumlah</h5>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inv as $item)
                                    <tr>
                                        <td class="col-md-9">{{$item->nama_customer}}</td>
                                        <td class="col-md-3">Rp. {{$item->total}}
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right">
                                            <p> <strong>Jumlah total: </strong> </p>
                                            <p> <strong>Diskon: </strong> </p>
                                            <p> <strong>Jumlah dibayar: </strong> </p>
                                        </td>
                                        <td>
                                            <p> <strong>Rp.
                                                {{$total}}</strong> </p>
                                            <p> 
                                            </p>
                                            <p> <strong>Rp.
                                                    </strong> </p>
                                        </td>
                                    </tr>
                                    <tr style="color: #F81D2D;">
                                        <td class="text-right">
                                            <h4><strong>Total:</strong></h4>
                                        </td>
                                        <td class="text-left">
                                            <h4><strong>Rp. {{$total}}
                                                </strong></h4>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@section('css')
<style>
    .img {
        height: 100px
    }

    h1 {
        text-align: center
    }

</style>
@endsection
@section('js')
<script src="https://use.fontawesome.com/19ea7ee06b.js"></script>
@endsection