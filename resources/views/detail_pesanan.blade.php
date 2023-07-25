@extends('layout.index')
@section('content')
<div class="content">
  <div class="container">
    <div class="d-flex justify-content-center row">
      <div class="container-fluid">
        <h3>Detail Pesanan</h3>
        <a href="{{ route('print.order',$pesanan[0]->id) }}" class="btn btn-sm btn-primary">Print</a>
        &nbsp;
        @if ($pesanan[0]->stts == 2)
        <a href="{{ route('proses.order',$pesanan[0]->id) }}" class="btn btn-sm btn-success">Proses Pesanan</a>
        @endif
        <div class="row mt-3">
          <aside class="col-md-12">
            <div class="card">
              <div class="card-title">
                <h5>List Pesanan</h5>
              </div>
              <div class="card-body">
                <table>
                  <tr>
                    <td>CUSTOMER</td>
                    <td>:</td>
                    <td>{{ $pesanan?$pesanan[0]->nama_customer:'-' }}</td>
                  </tr>
                  <tr>
                    <td>STATUS</td>
                    <td>:</td>
                    <td>
                      {{-- {{ dd($pesanan?$pesanan[0]->stts:'-') }} --}}
                      @if ($pesanan[0]->stts == 0)
                      <span class="badge bg-warning text-dark">Sedang Di proses</span>
                      @elseif ($pesanan[0]->stts == 1)
                      <span class="badge bg-success text-white">Selesai</span>
                      @else
                      <span class="badge bg-secondary text-white">Belum Bayar</span>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>TOTAL</td>
                    <td>:</td>
                    <td>@currency($pesanan?$pesanan[0]->total:'-')</td>
                  </tr>
                  <tr>
                    <td>PESANAN </td>
                    <td colspan="2">:</td>
                  </tr>
                  @foreach ($pesanan as $item)
                    @foreach ($item->getDetail as $items)
                    <tr>
                      <td colspan="2"></td>
                      <td>
                        <img src="{{ asset($items->getProduk->gambar) }}" alt="" height="100px"> &nbsp; {{ $items->getProduk->nama }} x{{ $items->qty }} @currency($items->getProduk->harga*$items->qty)
                      </td>
                    </tr>
                    @endforeach
                    @endforeach
                </table>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection