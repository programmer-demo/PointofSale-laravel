<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Sell;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $start_date = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));

//        $tanggalAkhir = date('Y-m-d');
        $end_date = date('Y-m-d');

//        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
//            $tanggalAwal = $request->tanggal_awal;
//            $tanggalAkhir = $request->tanggal_akhir;
//        }

        if ($request->has('start_date') && $request->start_date != "" && $request->has('end_date') && $request->end_date) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        }

//        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir'));
        return view('laporan.index', compact('start_date', 'end_date'));
    }

//    public function getData($awal, $akhir)
    public function getData($start, $end)
    {
        $no = 1;
        $data = array();
//        $pendapatan = 0;
        $income = 0;

//        $total_pendapatan = 0;
        $total_income = 0;

//        while (strtotime($awal) <= strtotime($akhir)) {
        while (strtotime($start) <= strtotime($end)) {
//            $tanggal = $awal;
            $date = $start;

//            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));
            $start = date('Y-m-d', strtotime("+1 day", strtotime($start)));

//            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_sales = Sell::where('created_at', 'LIKE', "%$date%")->sum('accepted');

//            $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_purchases = Purchase::where('created_at', 'LIKE', "%$date%")->sum('total_price');

//            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');
            $total_expenses = Expense::where('created_at', 'LIKE', "%$date%")->sum('amount');

//            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $income = $total_sales - $total_purchases - $total_expenses;

//            $total_pendapatan += $pendapatan;
            $total_income += $income;

            $row = array();
            $row['DT_RowIndex'] = $no++;
//            $row['tanggal'] = tanggal_indonesia($tanggal, false);
//            $row['penjualan'] = format_uang($total_penjualan);
//            $row['pembelian'] = format_uang($total_pembelian);
//            $row['pengeluaran'] = format_uang($total_pengeluaran);
//            $row['pendapatan'] = format_uang($pendapatan);

            $row['tanggal'] = tanggal_indonesia($date, false);
            $row['penjualan'] = format_uang($total_sales);
            $row['pembelian'] = format_uang($total_purchases);
            $row['pengeluaran'] = format_uang($total_expenses);
            $row['pendapatan'] = format_uang($pendapatan);

            $data[] = $row;
        }
        // visit "codeastro" for more projects!
        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => '',
            'pembelian' => '',
            'pengeluaran' => 'Total Income',
            'pendapatan' => format_uang($total_pendapatan),
        ];

        return $data;
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
        //
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
        //
    }
}
