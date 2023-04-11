<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use App\Models\produk;
use App\Models\cart;
use App\Models\order;
use DB;

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

        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];
        // Dapatkan nilai pencarian dari permintaan
        $cari = $request->input('cari');

        // Cari di kolom judul dan isi dari tabel posting
        $produk = produk::query()
            ->where('nama', 'LIKE', "%{$cari}%")
            ->get();

        // Kembalikan tampilan pencarian dengan hasil yang dipadatkan
        // dd($produk);
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

    public function cart(Request $request, $id=null)
    {
        $nilai_diskon = 0;
        if ($nilai_diskon=0) {
            return redirect('/')->with('kosong','Keranjang kosong, silahkan pilih menu di bawah');
        } else {
        // recomendasi produk
        $produk = DB::select('SELECT * FROM produk ORDER BY created_at');

        // untuk di navbar keranjang
        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];

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
                    stts = 0
        ");

        $s = DB::select("SELECT id from cart where id=?",[$id]);

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
        
        return view('cart',compact('carts','keranjang','total_amount','nilai_diskon','total','produk','s'));
        }
       
    }

    public function addToCart(Request $request)
    {

        if($request->isMethod('post')){
            // return $request;
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

    public function order(Request $request, $id=null)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            if ($request->jumlah_bayar < $request->total) {
                return redirect()->back()->with(['error' => 'Nominal kurang dari tagihan']);
            } else {
                foreach ($request->id_produk as $key => $value) {
                    $o = new order;
                    $o->id_produk = $value;
                    $o->nama_customer = $data['customer'];
                    $o->jumlah_produk = $data['jumlah_produk'];
                    $o->jumlah_bayar = $data['jumlah_bayar'];
                    $o->total = $data['total'];
                    $o->save();
                }
                
                $kembalian = $data['jumlah_bayar'] - $data['total'];
            }
            
        }
        $d=DB::delete("DELETE from cart");
        
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
        $o=DB::delete("DELETE from pos_percent.order where id_produk=?",[$id]);
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
            pos_percent.order a
                LEFT JOIN
            produk b ON a.id_produk = b.id
        GROUP BY nama");

        // total jual produk
        $tjp = DB::select("SELECT 
            b.nama, count(b.nama) as total_produk
        FROM
            pos_percent.order a
                LEFT JOIN
            produk b ON a.id_produk = b.id
            GROUP BY b.nama");

        $keranjang = DB::select("SELECT count(*) as keranjang FROM cart WHERE stts=0")[0];

        return view('laporan_new', compact('keranjang', 'nama', 'tjp'));
    }

    public function destroy($id=null){
        $d=DB::delete("DELETE from produk where id=?",[$id]);
        return redirect('/tambah-produk')->with('messagehapus','data berhasil di hapus!!!');
    }
}
