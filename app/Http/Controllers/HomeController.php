<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use App\Models\produk;
use App\Models\cart;
use App\Models\meja;
use App\Models\order;
use App\Models\order_detail;
use DB;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Session;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $carts = DB::select("
                SELECT 
                    a.*, jumlah_produk, jumlah_produk*a.harga as total, id_produk
                FROM
                produk a
                    RIGHT JOIN
                cart b ON a.id = b.id_produk"
        );
        if ($carts) {
            $carts=$carts[0];
        }

        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0 and no_meja is null")[0];
        // Dapatkan nilai pencarian dari permintaan
        $cari = $request->input('cari');

        // Cari di kolom judul dan isi dari tabel posting
        $produk = produk::query()
            ->where('nama', 'LIKE', "%{$cari}%")
            ->get();

        return view('home',compact('produk','keranjang','carts'));
    }

    public function tambah_produk(Request $request)
    {
        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];

        if($request->isMethod('post')){
            $data = $request->all();
            $m = new produk;
            $m->nama = $data['nama'];
            $m->deskripsi = $data['deskripsi'];
            $m->harga = $data['harga'];
            if($request->hasfile('gambar')){
                $files = $request->file('gambar');
                $extension = $files->getClientOriginalName();
                $large_image_path = 'images/produk/'.$extension;
                $files->move('images/produk/', $large_image_path);  
                $m->gambar = $large_image_path;
               }
            $m->save();
            return redirect('/')->with('message','data berhasil di simpan');
         }

        return view('tambah-produk',compact('keranjang'));
    }

    public function produk()
    {
        $produk = DB::select("SELECT * FROM produk");
        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];

        return view('daftar-produk',compact('produk','keranjang'));
    }

    public function ubah_produk(request $request, $id)
    {
        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];
        $produk = Produk::find($id);
        if ($request->isMethod('POST')) {
            if($request->hasfile('gambar')){
                $files = $request->file('gambar');
                $extension = $files->getClientOriginalName();
                $large_image_path = 'images/produk/'.$extension;
                $files->move('images/produk/', $large_image_path);  
                $gambar = $large_image_path;
               } else {
                $gambar = $produk->gambar;
               }
            $produk->update([
                'nama' => $request->nama,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'gambar' => $gambar
            ]);
        }

        return view('ubah-produk', compact('produk','keranjang'));
    }

    public function hapus_produk($id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return redirect()->back();
    }

    public function cart(Request $request, $id_meja)
    {
        if ($id_meja != 'NULL') {
             // untuk di navbar keranjang
             $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0 and no_meja = $id_meja")[0];

             $carts = DB::select("
                     SELECT 
                         a.*,
                         jumlah_produk,
                         jumlah_produk * a.harga AS total,
                         id_produk,
                         stts
                     FROM
                         produk a
                             RIGHT JOIN
                         cart b ON a.id = b.id_produk
                     WHERE
                         stts = 0 and no_meja is not null
             ");
 
             // hitung keranjang
             $total_amount = 0;
             foreach($carts as $item){
             $total_amount = $total_amount + ($item->harga * $item->jumlah_produk);
             }
 
             // hitung keseluruhan
             $total = 0;
             foreach($carts as $item1){
                 $total = $total + ($item1->harga * $item1->jumlah_produk);
             }
             $id_meja = $id_meja;
             
             return view('cart',compact('carts','keranjang','total_amount','total','id_meja'));
        } else {
            $nilai_diskon = 0;
            if ($nilai_diskon=0) {
                return redirect('/')->with('kosong','Keranjang kosong, silahkan pilih menu di bawah');
            } else {
            $produk = DB::select('SELECT * FROM produk ORDER BY created_at');
            // untuk di navbar keranjang
            $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0 and no_meja is null")[0];

            $carts = DB::select("
                    SELECT 
                        a.*,
                        jumlah_produk,
                        jumlah_produk * a.harga AS total,
                        id_produk,
                        stts
                    FROM
                        produk a
                            RIGHT JOIN
                        cart b ON a.id = b.id_produk
                    WHERE
                        stts = 0 and no_meja is null
            ");

            // hitung keranjang
            $total_amount = 0;
            foreach($carts as $item){
            $total_amount = $total_amount + ($item->harga * $item->jumlah_produk);
            }
            
            // hitung diskon
            $i = $request->diskon;
            foreach ($carts as $key) {
                $nilai_diskon = ($i/100)*$total_amount;
            }

            // hitung keseluruhan
            $total = 0;
            foreach($carts as $item1){
                $total = $total + ($item1->harga * $item1->jumlah_produk) - $nilai_diskon;
            }
            
            return view('cart',compact('carts','keranjang','total_amount','nilai_diskon','total','produk'));
            }
        }
    }

    public function addToCart(Request $request)
    {

        $r = DB::select("SELECT count(id_produk) as id_produk FROM cart where id_produk=? and stts=0",[$request->id_produk])[0];
                $produk = Produk::find($request->id_produk);
                $produk = $produk->nama;

                if ($r->id_produk == 0) {
                    $data = $request->all();
                    $cart = new cart;
                    $cart->id_produk = $data['id_produk'];
                    $cart->stts = '0';
                    $cart->save();
        
                    return redirect()->back()->with(['message' => $produk.' berhasil dimasukkan ke keranjang']);
        }
    }
    public function addToCartCust(Request $request, $id_meja)
    {

        if($request->isMethod('post')){
            if ($id_meja) {
                $r = DB::select("SELECT count(id_produk) as id_produk FROM cart where id_produk=? and no_meja is not null",[$request->id_produk])[0];
                $produk = Produk::find($request->id_produk);
                $produk = $produk->nama;

                if ($r->id_produk == 0) {
                    $data = $request->all();
                    $cart = new cart;
                    $cart->id_produk = $data['id_produk'];
                    $cart->stts = '0';
                    $cart->no_meja = $id_meja;
                    $cart->save();
        
                    return redirect()->back()->with(['message' => $produk.' berhasil dimasukkan ke keranjang']);
                }else {
                    // return 'gagal';
                    return redirect()->back();
                }  
            } else {
                $r = DB::select("SELECT count(id_produk) as id_produk FROM cart where id_produk=? and stts=0",[$request->id_produk])[0];
                $produk = Produk::find($request->id_produk);
                $produk = $produk->nama;

                if ($r->id_produk == 0) {
                    $data = $request->all();
                    $cart = new cart;
                    $cart->id_produk = $data['id_produk'];
                    $cart->stts = '0';
                    $cart->save();
        
                    return redirect()->back()->with(['message' => $produk.' berhasil dimasukkan ke keranjang']);
                }else {
                    return redirect()->back();
                }  
            }
        }
    }

    public function order(Request $request, $id_meja)
    {
        if ($request->isMethod('post')) {
            if ($id_meja) {
                $data = $request->all();
                if ($request->jumlah_bayar < $request->total) {
                    return redirect()->back()->with(['error' => 'Pembayaran kurang dari total bayar']);
                } else {
                    $o = new order;
                    $o->nama_customer = $data['customer'];
                    $o->jumlah_bayar = $data['jumlah_bayar'];
                    $o->no_meja = $id_meja;
                    $o->total = $data['total'];
                    $o->save();
    
                    $cart = cart::where('no_meja', $id_meja)->get();
                    foreach($cart as $value) {
                        $m = new order_detail;
                        $m->order_id = $o->id;
                        $m->produk_id = $value->id_produk;
                        $m->qty = $value->jumlah_produk;
                        $m->save();
                        $value->delete();
                    }
                    $kembalian = $data['jumlah_bayar'] - $data['total'];
                }
            } else {
                $data = $request->all();
                if ($request->jumlah_bayar < $request->total) {
                    return redirect()->back()->with(['error' => 'Pembayaran kurang dari total bayar']);
                } else {
                    $o = new order;
                    $o->nama_customer = $data['customer'];
                    $o->jumlah_bayar = $data['jumlah_bayar'];
                    $o->total = $data['total'];
                    $o->save();
    
                    $cart = cart::all();
                    foreach($cart as $value) {
                        $m = new order_detail;
                        $m->order_id = $o->id;
                        $m->produk_id = $value->id_produk;
                        $m->qty = $value->jumlah_produk;
                        $m->save();
                    }
                    $kembalian = $data['jumlah_bayar'] - $data['total'];
                    $d=DB::delete("DELETE from cart where no_meja is null");
                }
            }
        }
        
        return redirect()->back()->with('bayar','Kembalian: Rp. '.number_format($kembalian,0));
    }

    public function cart_quantity(Request $request,$id=null,$quantity=null)
    {
        $c = DB::select("SELECT * FROM cart WHERE id_produk=?",[$id])[0];
        $updated_quantity = $c->jumlah_produk+$quantity;
        $r = Cart::where(['id_produk'=>$id])->update(['jumlah_produk'=>$updated_quantity]);

        return redirect()->back()->with('messageupdated','The product has been updated');
    }

    public function hapus_cart($id=null)
    {
        $d=DB::delete("DELETE from cart where id_produk=?",[$id]);
        $o = order::where('id_produk', '=', $id)->delete();


        return redirect('/cart')->with('messagehapus','data berhasil di hapus!!!');
    }

    public function invoice(Request $request)
    {
        $keranjang = DB::select("SELECT COUNT(*) as keranjang FROM cart WHERE stts=0")[0];

        $inv = DB::select("SELECT * FROM pos_percent.order WHERE nama_customer=?",[$customer]);
        if ($inv) {
            $inv=$inv[0];
        }
        $carts = DB::select("
                SELECT 
                    a.*,
                    jumlah_produk,
                    jumlah_produk * harga AS total,
                    id_produk,
                    stts
                FROM
                    produk a
                        RIGHT JOIN
                    cart b ON a.id = b.id_produk
                WHERE
                    stts = 0
        ");
        $nilai_diskon = 0;
        $total = 0;
        foreach($carts as $item1){
            $total = $total + ($item1->harga * $item1->jumlah_produk) - $nilai_diskon;
        }

        $bayar = 0;
        foreach ($carts as $key1) {
            $bayar = $bayar - $total;
        }

        return view('invoice',compact('keranjang','inv','total','bayar'));
    }

    public function transaksi()
    {
        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];
        $transaksi = DB::select("SELECT 
                id_produk,
                b.nama,
                nama_customer,
                jumlah_produk,
                total,
                a.created_at
            FROM
                pos_percent.order a
                    LEFT JOIN
                pos_percent.produk b ON a.id_produk = b.id
            ORDER BY created_at DESC");

        return view('transaksi',compact('keranjang','transaksi'));
    }

    public function master_meja(request $request)
    {
        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];
        $meja = meja::all();
        if ($request->isMethod('POST')) {
            $n = new meja;
            $n->nama = $request->nama;
            $n->save();

            return redirect()->back();
        }

        return view('master_meja', compact('keranjang', 'meja'));
    }

    public function edit_meja(request $request, $id){
        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];
        $meja = meja::find($id);
        if ($request->isMethod('post')) {
            $meja->update([
                'nama' => $request->nama
            ]);

            return redirect('/master-meja');
        }

        return view('master_meja_edit', compact('meja','keranjang'));
    }

    public function hapus_meja($id)
    {
        $m = meja::find($id);
        $m->delete();

        return redirect()->back();
    }

    public function laporan_new(request $request)
    {
        if(request()->ajax())
        {
            if(!empty($request->from_date))
            {
                $data = DB::table('order')
                ->whereBetween('created_at', array($request->from_date, $request->to_date))
                ->groupBy('nama_customer')
                ->get();
            }
            else
            {
                $data = DB::table('order')
                ->groupBy('nama_customer')
                ->get();
            }
            return datatables()->of($data)->make(true);
        }

        // daftar nama produk
        $nama = DB::select("SELECT 
            nama
        FROM
            pos.order_detail a
                LEFT JOIN
            produk b ON a.produk_id = b.id
        GROUP BY nama");

        // total jual produk
        $tjp = DB::select("SELECT 
            b.nama, count(b.nama)*qty as total_produk
        FROM
            pos.order_detail a
                LEFT JOIN
            produk b ON a.produk_id = b.id
            GROUP BY b.nama");
        

        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];

        return view('laporan_new', compact('keranjang', 'nama', 'tjp'));
    }

    public function pesanan()
    {
        $pesanan = order::get();
        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];

        return view('pesanan', compact('pesanan','keranjang'));
    }

    public function pesanan_selesai($id)
    {
        $pesanan = order::find($id);
        $pesanan->update([
            'stts' => 1,
        ]);

        return redirect()->back();
    }

    public function pesanan_detail($id)
    {
        $pesanan = order::where('id', '=' ,$id)->with(['getDetail','getDetail.getProduk'])->get();
        // return $pesanan[0]->getDetail[0]->getProduk->nama;

        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];

        return view('detail_pesanan', compact('pesanan', 'keranjang'));
    }

    public function print_pesanan_detail($id)
    {
        $pesanan = order::where('id', '=' ,$id)->with(['getDetail','getDetail.getProduk'])->get();
        return view('print', compact('pesanan'));
    }

    public function pesan_by_meja($id)
    {
        return 'customer';
    }

    public function destroy($id=null){
        $d=DB::delete("DELETE from produk where id=?",[$id]);
        return redirect('/tambah-produk')->with('messagehapus','data berhasil di hapus!!!');
    }

    // customer
    public function index_customer(request $request, $id_meja)
    {
        $carts = DB::select("
                SELECT 
                    a.*, jumlah_produk, jumlah_produk*a.harga as total, id_produk
                FROM
                produk a
                    RIGHT JOIN
                cart b ON a.id = b.id_produk"
        );
        if ($carts) {
            $carts=$carts[0];
        }

        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0 and no_meja is not null")[0];
        // Dapatkan nilai pencarian dari permintaan
        $cari = $request->input('cari');

        // Cari di kolom judul dan isi dari tabel posting
        $produk = produk::query()
            ->where('nama', 'LIKE', "%{$cari}%")
            ->get();
        $id_meja = $id_meja;

        return view('home_customer',compact('produk','keranjang','carts','id_meja'));
    }
}
