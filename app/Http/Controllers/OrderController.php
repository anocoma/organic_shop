<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Mail\OrderConfirm;
use App\Models\DonHang;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donHang = Auth::user()->donHang;

        $trangThaiDonHang = DonHang::TRANG_THAI_DON_HANG;

        return view('clients.donhangs.index',compact('donHang','trangThaiDonHang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $carts = session()->get('cart', []);
        if (!empty($carts)){
            $total = 0;
            $subTotal = 0;

            foreach ($carts as $item){
                $subTotal += $item['gia'] * $item['so_luong'];
            }
            $shipping = 30000;
            $total = $subTotal + $shipping;

            return view('clients.donhangs.create', compact('carts','subTotal','shipping','total',));
        }
        return redirect()->route('cart.list');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        if ($request->isMethod('POST')){
            DB::beginTransaction();

            try {
                $params = $request->except('_token');
                $params['ma_don_hang'] = $this->generateUniqueOrderCode();
                $donHang = DonHang::query()->create($params);
                $donHangId = $donHang->id;
                $carts = session()->get('cart', []);

                foreach ($carts as $key => $item){
                    $thanhTien =  $item['gia'] * $item['so_luong'];
                    $donHang->chiTietDonHang()->create([
                        'don_hang_id' => $donHangId,
                        'san_pham_id' => $key,
                        'don_gia' => $item['gia'],
                        'so_luong' => $item['so_luong'],
                        'thanh_tien' => $thanhTien
                    ]);

                    $sanPham = SanPham::find($key);
                    if ($sanPham) {
                        $sanPham->so_luong -= $item['so_luong'];
                        $sanPham->save();
                    }
                }

                DB::commit();

                Mail::to($donHang->email_nguoi_nhan)->queue(new OrderConfirm($donHang));

                session()->put('cart', []);
                return redirect()->route('donhangs.index')->with('success', 'Đơn hàng được tạo thành công');
            }catch (\Exception $e){
                DB::rollBack();
                return redirect()->route('cart.list')->with('error','Có lỗi khi tạo đơn hàng, thử lại sau' /*. $e->getMessage()*/);
            }

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $donHang = DonHang::query()->findOrFail($id);
        $trangThaiDonHang = DonHang::TRANG_THAI_DON_HANG;
        $trangThaiThanhToan = DonHang::TRANG_THAI_THANH_TOAN;

        return view('clients.donhangs.show',compact('donHang', 'trangThaiDonHang', 'trangThaiThanhToan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    function generateUniqueOrderCode()
    {
        do {
            $orderCode = 'ORD-' . Auth::id() . '-' . now()->timestamp;
        } while (DonHang::where('ma_don_hang', $orderCode)->exists());
        return $orderCode;
    }
}
