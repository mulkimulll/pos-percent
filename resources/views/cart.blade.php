@extends('layout.index')
@section('css')
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="container">
            <div class="d-flex justify-content-center row">
                <div class="container-fluid">
                    <div class="row">
                        <aside class="col-lg-9">
                            @if ($message = Session::get('error'))
                                <div class="alert alert-warning" role="alert">
                                    <i class="far fa-bell"></i> {{ $message }}
                                </div>
                            @endif
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-shopping-cart">
                                        <thead class="text-muted">
                                            <tr class="small text-uppercase">
                                                <th scope="col">Produk</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Harga</th>
                                                <th scope="col" class="text-right d-none d-md-block" width="100"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($carts as $cart)
                                                <tr>
                                                    <td>
                                                        <figure class="itemside align-items-center">
                                                            <div class="aside">
                                                                <img src="{{ asset($cart->gambar) }}" class="img-sm">
                                                            </div>
                                                            <figcaption class="info"> <a href="#"
                                                                    class="title text-dark"
                                                                    data-abc="true">{{ $cart->nama }}</a>
                                                            </figcaption>
                                                        </figure>
                                                    </td>
                                                    <td>
                                                        <div class="form-group row">
                                                            @if ($cart->jumlah_produk > 1)
                                                                <a class="col-sm-2 col-form-label"
                                                                    href="{{ url('/cart-quantity/' . $cart->id_produk . '/-1') }}"><i
                                                                        class="fa fa-minus text-danger"></i></a>
                                                            @endif
                                                            <div class="col-sm-8">
                                                                <input type="number" name="qty[]"
                                                                    class="form-control" style="text-align:center;" value="{{ $cart->jumlah_produk }}"
                                                                    min="1" disabled>
                                                            </div>
                                                            <a class="col-sm-2 col-form-label"
                                                                href="{{ url('/cart-quantity/' . $cart->id_produk . '/1') }}"><i
                                                                    class="fa fa-plus text-success"></i></a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class=""> <var class="price">@currency($cart->total)</var>
                                                            {{-- <small class="text-muted"> $9.20 each </small>  --}}
                                                        </div>
                                                    </td>
                                                    <td class="text-right d-none d-md-block">
                                                        <a href="{{ url('hapus-cart/' . $cart->id) }}" class="btn btn-danger"
                                                            data-abc="true">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2">
                                                    Jumlah
                                                </td>
                                                <td class="price">
                                                    @currency($total_amount)
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </aside>
                        <aside class="col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    @if (Auth::user())
                                    <form action="{{ url('order') }}" method="post">
                                        @csrf
                                        <div class="form-group"> <label>Nama customer</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="customer"
                                                    placeholder="nama" required>
                                            </div>
                                        </div>
                                        <dl class="dlist-align">
                                            <dt>Total:</dt>
                                            <dd class="text-right ml-3">@currency($total_amount)</dd>
                                        </dl>
                                            <div class="form-group"> <label>Jumlah bayar</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control coupon" name="jumlah_bayar"
                                                        placeholder="Rp.">
                                                </div>
                                            </div>
                                        <hr>
                                        @foreach ($carts as $item)
                                            <input type="text" class="form-control coupon" name="id_produk[]"
                                                value="{{ $item->id_produk }}" hidden>
                                            <input type="text" class="form-control" name="total"
                                                value="{{ $total }}" hidden>
                                            <input type="number" name="jumlah_produk[]" class="form-control"
                                                value="{{ $cart->jumlah_produk }}" hidden>
                                        @endforeach
                                        <div class="row">
                                            <div class="col-md-12">
                                                {{-- <button type="submit" class="btn btn-primary btn-block btn-bayar"><i class="fa fa-money-bill"></i> Bayar Sekarang
                                              </button> --}}
                                              
                                              @if(empty($carts))
                                              <button class="btn btn-out btn-success btn-square btn-main mb-2" disabled>
                                                    <i class="fa fa-money-bill"></i> Bayar Ke kasir
                                                </button>
                                               @else
                                              <button class="btn btn-out btn-success btn-square btn-main mb-2">
                                                    <i class="fa fa-money-bill"></i> Bayar Ke kasir
                                                </button>
                                              @endif
                                            </div>
                                    </form>
                                    @else
                                    <form action="{{ url('order-cust/'.$id_meja) }}" method="post">
                                        @csrf
                                        <div class="form-group"> <label>Nama customer</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="customer"
                                                    placeholder="nama" required>
                                            </div>
                                        </div>
                                        <dl class="dlist-align">
                                            <dt>Total:</dt>
                                            <dd class="text-right ml-3">@currency($total_amount)</dd>
                                        </dl>
                                            <div class="form-group"> <label>Jumlah bayar</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control coupon" name="jumlah_bayar"
                                                        placeholder="Rp.">
                                                </div>
                                            </div>
                                        <hr>
                                        @foreach ($carts as $item)
                                            <input type="text" class="form-control coupon" name="id_produk[]"
                                                value="{{ $item->id_produk }}" hidden>
                                            <input type="text" class="form-control" name="total"
                                                value="{{ $total }}" hidden>
                                            <input type="number" name="jumlah_produk[]" class="form-control"
                                                value="{{ $cart->jumlah_produk }}" hidden>
                                        @endforeach
                                        <div class="row">
                                            <div class="col-md-12">
                                                {{-- <button type="submit" class="btn btn-primary btn-block btn-bayar"><i class="fa fa-money-bill"></i> Bayar Sekarang
                                              </button> --}}
                                              @if(empty($carts))
                                              <button class="btn btn-out btn-success btn-square btn-main mb-2" disabled>
                                                    <i class="fa fa-money-bill"></i> Bayar Ke kasir
                                                </button>
                                              
                                              @else
                                              <button class="btn btn-out btn-success btn-square btn-main mb-2">
                                                    <i class="fa fa-money-bill"></i> Bayar Ke kasir
                                                </button>
                                            @endif
                                                
                                            </div>
                                    </form>
                                    @endif
                                    {{-- <button class="btn btn-out btn-warning btn-square btn-main">
                                        Bayar nanti
                                    </button> --}}
                                </div>
                            </div>
                    </div>
                    </aside>
                    <!-- Modal Kembalian -->
                    <div class="modal modal-danger fade" id="modal-kembalian">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" id="close" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">×</span></button>
                                </div>
                                <div class="modal-body">
                                    <h1 class="kembalian"></h1>
                                </div>
                                <div class="modal-footer">
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var flash = "{{ Session::has('bayar') }}";
            if (flash) {
                var bayar = "{{ Session::get('bayar') }}";
                $('.kembalian').text(bayar);
                $('#modal-kembalian').modal();
            }

            var total = "{{ 'Rp. ' . number_format($total, 0) }}";
            $('.total').text(total);

            $('div.dataTables_filter input').focus();

            $('.table-barang').DataTable({
                "pageLength": 5,
                processing: true,
                serverSide: true,
                ajax: "{{ url('yajra') }}",
                columns: [
                    // or just disable search since it's not really searchable. just add searchable:false
                    {
                        data: 'rownum',
                        name: 'rownum'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                ]
            });
        })
    </script>
@endsection
