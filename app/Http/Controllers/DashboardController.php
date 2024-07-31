<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Product;
use App\Models\Produk;
use App\Models\Purchase;
use App\Models\Sell;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
//        $kategori = Kategori::count();
        $category = Category::count();

//        $produk = Produk::count();
        $product = Product::count();

        $supplier = Supplier::count();
        $member = Member::count();

        $sell = Sell::sum('accepted');
//        $penjualan = Penjualan::sum('diterima');

        $expense = Expense::sum('amount');
//        $pengeluaran = Pengeluaran::sum('nominal');

        $purchase = Purchase::sum('total_price');
//        $pembelian = Pembelian::sum('bayar');

//        $tanggal_awal = date('Y-m-01');
        $start_date = date('Y-m-01');

//        $tanggal_akhir = date('Y-m-d');
        $end_date = date('Y-m-d');

//        $data_tanggal = array();
        $date_data = array();

//        $data_pendapatan = array();
        $income_data = array();

        while (strtotime($start_date) <= strtotime($end_date)) {
            $date_data[] = (int) substr($start_date, 8, 2);

//            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$start_date%")->sum('bayar');
            $total_sales = Sell::where('created_at', 'LIKE', "%$start_date%")->sum('accepted');

//            $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$start_date%")->sum('bayar');
            $total_purchases = Purchase::where('created_at', 'LIKE', "%$start_date%")->sum('total_price');

//            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$start_date%")->sum('nominal');
            $total_expenses = Expense::where('created_at', 'LIKE', "%$start_date%")->sum('amount');

//            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $income = $total_sales - $total_purchases - $total_expenses;
//            $income_data[] += $pendapatan;
            $income_data[] += $income;

            $start_date = date('Y-m-d', strtotime("+1 day", strtotime($start_date)));
        }

        $start_date = date('Y-m-01');

        if (auth()->user()->level == 1) {
            return view('admin.dashboard', compact('category', 'product', 'supplier', 'member', 'purchase', 'expense', 'start_date', 'end_date', 'date_data', 'income_data' , 'sell'));
//            return view('admin.dashboard', compact('kategori', 'produk', 'supplier', 'member', 'penjualan', 'pengeluaran', 'pembelian', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
        } else {
            return view('kasir.dashboard');
        }
    }
}
// visit "codeastro" for more projects!
