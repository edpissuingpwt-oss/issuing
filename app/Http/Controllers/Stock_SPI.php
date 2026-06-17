<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class Stock_SPI extends Controller
{
    public function stockspi()
    {
        $query = "SELECT sts_kodeigr,sts_kodespi,sts_prdcd,prd_deskripsipanjang,st_saldoakhir,sts_qty 
                            from igrpwt.tbmaster_stock_spi_icc 
                            join igrpwt.tbmaster_prodmast on sts_prdcd = prd_prdcd and prd_prdcd like '%0'
                            join igrpwt.tbmaster_stock on sts_prdcd = st_prdcd and st_lokasi = '01'";
        
        $results = DB::select($query);

        return view('master.stockspi', compact('results'));
    }
}