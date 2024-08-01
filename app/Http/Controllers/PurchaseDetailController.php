<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchase_id = session('purchase_id');
        $product = Product::orderBy('name')->get();
        $supplier = Supplier::find(session('supplier_id'));
        $discount = Purchase::find($purchase_id)->discount ?? 0;
        if (! $supplier) {
//            abort(404);
        }
        return view('purchase_detail.index', compact('purchase_id', 'product', 'supplier', 'discount'));
    }

    public function data($id)
    {
        $detail = PurchaseDetail::with('product')
            ->where('purchase_id', $id)
            ->get();
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['code_product'] = '<span class="label label-success">'. $item->product['code_product'] .'</span';
            $row['name'] = $item->product['name'];
            $row['purchase_price']  = '$ '. format_uang($item->purchase_price);
            $row['amount']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id .'" value="'. $item->amount .'">';
            $row['subtotal']    = '$ '. format_uang($item->subtotal);
            $row['action']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('purchase_detail.destroy', $item->id_pembelian_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->purchase_price * $item->amount;
            $total_item += $item->amount;
        }
        $data[] = [
            'code_product' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'name' => '',
            'purchase_price'  => '',
            'amount'      => '',
            'subtotal'    => '',
            'action'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['action', 'code_product', 'amount'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::where('id', $request->id)->first();
        if (! $product) {
            return response()->json('Data failed to save', 400);
        }

        $detail = new PurchaseDetail();
        $detail->purchase_id = $request->purchase_id;
        $detail->product_id = $product->id;
        $detail->purchase_id = $product->purchase_price;
        $detail->amount = 1;
        $detail->subtotal = $product->purchase_price;
        $detail->save();

        return response()->json('Data saved successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $detail = PurchaseDetail::find($id);
        $detail->amount = $request->amount;
        $detail->subtotal = $detail->purchase_price * $request->amount;
        $detail->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $detail = PurchaseDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($discount, $total)
    {
        $buyer = $total - ($discount / 100 * $total);
        $data  = [
            'totalRp' => format_uang($total),
            'buyer' => $buyer,
            'buyerRp' => format_uang($buyer),
            'spelled_out' => ucwords(terbilang($buyer). ' Dollar')
        ];

        return response()->json($data);
    }
}
