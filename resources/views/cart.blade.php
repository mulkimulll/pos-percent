@extends('layout.index')
@section('content')
<div class="content">
    <div class="container">
        <div class="d-flex justify-content-center row">
            <div class="container-fluid">
                <div class="row">
                    <aside class="col-lg-9">
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-borderless table-shopping-cart">
                                    <thead class="text-muted">
                                        <tr class="small text-uppercase">
                                            <th scope="col">Produk</th>
                                            <th scope="col" width="120">Jumlah</th>
                                            <th scope="col" width="150">Harga</th>
                                            <th scope="col" class="text-right d-none d-md-block" width="100"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($carts as $cart)
                                        <tr>
                                            <td>
                                                <figure class="itemside align-items-center">
                                                    <div class="aside">
                                                        <img src="{{$cart->gambar}}" class="img-sm">
                                                    </div>
                                                    <figcaption class="info"> <a href="#" class="title text-dark"
                                                            data-abc="true">{{$cart->nama}}</a>
                                                        <p class="text-muted small">Keterangan: </p>
                                                    </figcaption>
                                                </figure>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    @if ($cart->jumlah_produk == 1)
                                                        
                                                    <a class="col-sm-2 col-form-label"
                                                        href="{{url('/cart-quantity/'.$cart->id_produk.'/-1')}}"><i
                                                            class="fa fa-minus text-danger"></i></a>
                                                    @endif
                                                    <div class="col-sm-8">
                                                        <input type="number" name="jumlah_produk[]" class="form-control"
                                                            value="{{$cart->jumlah_produk}}" readonly>
                                                    </div>
                                                    <a class="col-sm-2 col-form-label"
                                                        href="{{url('/cart-quantity/'.$cart->id_produk.'/1')}}"><i
                                                            class="fa fa-plus text-success"></i></a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class=""> <var class="price">Rp. {{$cart->total}}</var>
                                                    {{-- <small class="text-muted"> $9.20 each </small>  --}}
                                                </div>
                                            </td>
                                            <td class="text-right d-none d-md-block">
                                                <a href="{{ url('hapus-cart/'.$cart->id) }}" class="btn btn-light"
                                                    data-abc="true">
                                                    <i class="fa fa-trash"></i> Hapus
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
                                                Rp. {{$total_amount}}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </aside>
                    <aside class="col-lg-3">
                        <div class="card mb-3">
                            <div class="card-body">
                                <form action="" method="POST">
                                    @csrf
                                    <div class="form-group"> <label>Diskon %</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control coupon" name="diskon"
                                                placeholder="20">
                                            <span class="input-group-append">
                                                <button class="btn btn-primary btn-apply coupon">Gunakan</button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ url('order') }}" method="post">
                                    @csrf
                                <div class="form-group"> <label>Nama customer</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control coupon" name="customer"
                                            placeholder="junaidi">
                                    </div>
                                </div>
                                <dl class="dlist-align">
                                    <dt>Jumlah Harga:</dt>
                                    <dd class="text-right ml-3">Rp. {{$total_amount}}</dd>
                                </dl>
                                <dl class="dlist-align">
                                    <dt>Diskon:</dt>
                                    <dd class="text-right text-danger ml-3">{{$nilai_diskon}}</dd>
                                    <input type="text" class="form-control coupon" name="diskon"
                                        value="{{$nilai_diskon}}" hidden>
                                </dl>
                                <dl class="dlist-align">
                                    <dt>Total setelah diskon:</dt>
                                    <dd class="text-right text-dark b ml-3"><strong>Rp. {{$total}}</strong></dd>
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
                                        <input type="number" name="jumlah_produk" class="form-control"
                                                            value="{{$cart->jumlah_produk}}" hidden>
                                    @endforeach
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{-- <button type="submit" class="btn btn-primary btn-block btn-bayar"><i class="fa fa-money-bill"></i> Bayar Sekarang
                                              </button> --}}
                                            <button class="btn btn-out btn-success btn-square btn-main mb-2">
                                                <i class="fa fa-money-bill"></i> Bayar Sekarang
                                            </button>
                                        </div>
                                    </form>
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
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                  <h1><b><i class="kembalian"></i></b></h1>
                </div>
                <div class="modal-footer">
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
                    <div class="col">
                        <div class="bbb_viewed_title_container">
                            <h3 class="bbb_viewed_title">Rekomendasi Produk</h3>
                            <div class="bbb_viewed_nav_container">
                                <div class="bbb_viewed_nav bbb_viewed_prev"><i class="fas fa-chevron-left"></i></div>
                                <div class="bbb_viewed_nav bbb_viewed_next"><i class="fas fa-chevron-right"></i></div>
                            </div>
                        </div>
                        <div class="bbb_viewed_slider_container">
                            <div class="owl-carousel owl-theme bbb_viewed_slider">
                                @foreach ($produk as $item)
                                <div class="owl-item">
                                    <div class="bbb_viewed_item discount d-flex flex-column align-items-center justify-content-center text-center">
                                        <div class="bbb_viewed_image"><img src="{{$item->gambar}}" alt=""></div>
                                        <div class="bbb_viewed_content text-center">
                                            <div class="bbb_viewed_price">Rp. {{$item->harga}}</div>
                                            <div class="bbb_viewed_name"><a href="#">{{$item->nama}}</a></div>
                                            <div class="bbb_viewed_name"><form action="{{ url('/add-to-cart') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_produk" value="{{ $item->id }}">
                                                <input type="hidden" name="harga" value="{{ $item->harga }}">
                                                <button class="btn btn-sm btn-primary mt-4 text-white"><i class="fas fa-cart-plus"></i> beli</button>                               
                                            </form></div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
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
    $(document).ready(function(){
        var flash = "{{ Session::has('bayar') }}";
        if(flash){
            var bayar = "{{ Session::get('bayar') }}";
            $('.kembalian').text(bayar);
            $('#modal-kembalian').modal();
        }

        var total = "{{ 'Rp. '.number_format($total,0) }}";
        $('.total').text(total);

        $('div.dataTables_filter input').focus();

        $('.table-barang').DataTable({
            "pageLength": 5,
            processing: true,
            serverSide: true,
            ajax: "{{ url('yajra') }}",
            columns: [
                // or just disable search since it's not really searchable. just add searchable:false
                {data: 'rownum', name: 'rownum'},
                {data: 'nama', name: 'nama'},
            ]
        });

        // Ketika nama barang di klik
        $('body').on('click','.btn-barang',function(e){
            e.preventDefault();
            $(this).closest('tr').find('.loading').show();
            var id = $(this).attr('barang-id');
            var url = "{{ url('get') }}"+'/'+id;
            var _this = $(this);

            $.ajax({
                type:'get',
                url:url,
                success:function(data){
                    console.log(data);

                    $("input[name='nama']").val(data.nama);
                    $("input[name='harga_awal']").val(data.harga_awal);
                    $("input[name='discount']").val(data.discount);
                    $("input[name='harga_akhir']").val(data.harga_akhir);
                    $("input[name='barang_id']").val(data.barang_id);

                    _this.closest('tr').find('.loading').hide();
                }
            })
        });

        // Ketika submit di klik
        $('.btn-submit').click(function(e){
            e.preventDefault();
            var nama = $("input[name='nama']").val();
            if(nama == ''){
                // swal('Warning','Barang wajib dipilih terlebih dahulu','warning');
                alert('Barang wajib dipilih terlebih dahulu');
            }else{
                $(this).addClass('disabled');
                $(this).closest('form').submit();
            }
        })

        // Ketika btn selesai di klik
        $('.btn-selesai').click(function(e){
            e.preventDefault();
            var total = "{{ $total }}";
            var bayar = $("input[name='bayar']").val();

            if(bayar < total){
                alert('Uang Kurang');
            }else{
                $(this).closest('form').submit();
            }

        })

        $(document).keypress(function(e){
            if(e.which == 13){
                $('div.dataTables_filter input').focus();
                // $("Input[name='bayar']").focus();
            }
        })

        $(document).keypress(function(e){
            if(e.which == 118){
                // $('div.dataTables_filter input').focus();
                $("Input[name='bayar']").focus();
            }
        })

        // Ketika btn hapus transaksi di klik
        $('.btn-hapus').click(function(e){
            e.preventDefault();
            $('#modal-hapus-transaksi').modal();
        })

        // Ketika btn simpan transaksi disimpan
        $('.btn-simpan').click(function(e){
            e.preventDefault();
            $('#modal-simpan-transaksi').modal();
        })

    })
</script>
@endsection
