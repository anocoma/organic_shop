<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function listCart()
    {
        $cart = session()->get('cart', []);

        $total = 0;
        $subTotal = 0;

        foreach ($cart as $item){
            $subTotal += $item['gia'] * $item['so_luong'];
        }
        $shipping = 30000;

        $total = $subTotal + $shipping;

        return view('clients.cart', compact('cart','subTotal','shipping','total' ));
    }
    public function addCart(Request $request)
    {
        $proId = $request->input('product_id');
        $qty = $request->input('quantity');

        $sanPham = SanPham::query()->findOrFail($proId);

        $cart = session()->get('cart', []);

        if (isset($cart[$proId])){
            $cart[$proId]['so_luong'] += $qty;
        }else{
            $cart[$proId] = [
              'ten_san_pham' => $sanPham->ten_san_pham,
              'so_luong' => $qty,
              'gia' => isset($sanPham->gia_khuyen_mai) ? $sanPham->gia_khuyen_mai : $sanPham->gia,
              'hinh_anh' => $sanPham->hinh_anh,
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back();
    }
    public function updateCart(Request $request)
    {
        $cartNew = $request->input('cart',[]);
        session()->put('cart',$cartNew);
        return redirect()->back();
    }
}
