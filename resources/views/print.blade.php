<!DOCTYPE html>
<html>

<head>
  <title>Struk Pembayaran</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .container {
      width: 500px;
      margin: 0 auto;
      text-align: center;
      border: 1px solid #000;
      padding: 10px;
    }

    .header {
      margin-bottom: 10px;
    }

    .item {
      text-align: left;
      margin-bottom: 5px;
    }

    .total {
      font-weight: bold;
    }
  </style>
  <style type="text/css" media="print">
    .no-print {
      display: none;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2 class="header">Struk Pembayaran</h2>
    <div class="item total">
      <span>Nama Barang: </span>
    </div>
    <div class="item">
      @foreach ($pesanan as $item)
      @foreach ($item->getDetail as $items)
      <span>{{ $items->getProduk->nama }} x{{ $items->qty }} @currency($items->getProduk->harga*$items->qty)</span> <br>
      {{-- <span>{{ dd($pesanan) }}</span> --}}
      @endforeach
      @endforeach
    </div>
    <br>
    <div class="item total">
      <span>Total:</span>
      <span>@currency($pesanan?$pesanan[0]->total:'-')</span>
    </div>
    <hr>
    <div class="item">
      <span>Metode Pembayaran:</span>
      <span>Cash</span>
    </div>
    <div class="item">
      <span>Waktu Pembayaran:</span>
      <span>{{ $pesanan[0]->created_at }}</span>
    </div>
    <button class="no-print" onclick="window.print()">Cetak</button>
  </div>
</body>

</html>