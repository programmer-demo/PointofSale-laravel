<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplier = Supplier::orderBy('id')->get();

        return view('purchase.index', compact('supplier'));
    }

    public function data()
    {
        $purchases = Purchase::orderBy('id', 'desc')->get();

        return datatables()
            ->of($purchases)
            ->addIndexColumn()
            ->addColumn('qty', function ($purchases) {
                return format_uang($purchases->qty);
            })
            ->addColumn('total_price', function ($purchases) {
                return '$ '. format_uang($purchases->total_price);
            })
            ->addColumn('buyer', function ($purchases) {
                return '$ '. format_uang($purchases->supplier_id);
            })
            ->addColumn('date', function ($purchases) {
                return tanggal_indonesia($purchases->created_at, false);
            })
            ->addColumn('supplier', function ($purchases) {
                return $purchases->supplier->name;
            })
            ->editColumn('discount', function ($purchases) {
                return $purchases->discount . '%';
            })
            ->addColumn('action', function ($purchases) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('purchase.show', $purchases->id) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('purchase.destroy', $purchases->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $purchases = new Purchase();
        $purchases->supplier_id = $id;
        $purchases->qty  = 0;
        $purchases->total_price = 0;
        $purchases->discount      = 0;
        $purchases->buyer       = 0;
        $purchases->save();

        session(['purchase_id' => $purchases->id]);
        session(['supplier_id' => $purchases->supplier_id]);

        return redirect()->route('purchase_detail.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $purchases = Purchase::findOrFail($request->id);
        $purchases->qty = $request->qty;
        $purchases->total_price = $request->total_price;
        $purchases->discount = $request->discount;
        $purchases->buyer = $request->buyer;
        $purchases->update();

        $detail = PurchaseDetail::where('purchase_id', $purchases->id)->get();
        foreach ($detail as $item) {
            $product = Product::find($item->id);
            $product->stock += $item->amount;
            $product->update();
        }

        return redirect()->route('purchase.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = PurchaseDetail::with('product')->where('id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('code_product', function ($detail) {
                return '<span class="label label-success">'. $detail->product->code_product .'</span>';
            })
            ->addColumn('name', function ($detail) {
                return $detail->product->name;
            })
            ->addColumn('purchase_price', function ($detail) {
                return '$ '. format_uang($detail->purchase_price);
            })
            ->addColumn('amount', function ($detail) {
                return format_uang($detail->amount);
            })
            ->addColumn('subtotal', function ($detail) {
                return '$ '. format_uang($detail->subtotal);
            })
            ->rawColumns(['code_product'])
            ->make(true);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        $detail    = PurchaseDetail::where('purchase_id', $purchase->id)->get();
        foreach ($detail as $item) {
            $product = Product::find($item->id);
            if ($product) {
                $product->stcok -= $item->amount;
                $product->update();
            }
            $item->delete();
        }

        $purchase->delete();

        return response(null, 204);
    }
}
