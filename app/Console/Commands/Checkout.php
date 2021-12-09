<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use DB;

class Checkout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkout {product*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $order = $this->argument();
        $data = DB::table('products')->whereIn('code', $order['product'])->get();
        
        $total = 0;
        $free_cl = 0;
        $same_prod = [];
        $discount = [];
        if (!$data->isEmpty()) {
            $this->info($data->count().' product ditemukan');
            $check_karyawan = $this->ask('Apakah anda karyawan?(y/n)');
            if ($check_karyawan == 'y' || $check_karyawan == 'yes') {
                $nomor_karyawan = $this->ask('Berapa nomor karyawan anda?');
            }
            foreach ($order['product'] as $key => $value) {
                foreach ($data as $key2 => $value2) {
                    if ($value==$value2->code) {
                        $this->line($value2->name.' seharga Rp. '.number_format($value2->price,0,",","."));   
                        if (empty($same_prod[$value2->code])) {
                            $same_prod[$value2->code] = 1;
                        }else{
                            $same_prod[$value2->code] += 1;
                        }
                        if ($same_prod[$value2->code] > 3) {
                            $discount[$value2->code] = 20;
                            $this->info('Mendapatkan diskon 20% product '.$value2->name.', dari harga '.$value2->price.' jadi hanya membayar '.($value2->price-($value2->price*$discount[$value2->code]/100)).' harga product karena promo diskon 20% setiap pembelian lebih dari 3 minuman');  
                        }else{
                            $discount[$value2->code] = 0;
                        }
        
                        // Check Promo Caffe Latte
                        if (strpos($value2->code, 'CL') !== false && $free_cl == 0) {
                            $free_cl = 1;
                        }    else{
                            $total += $value2->price-($value2->price*$discount[$value2->code]/100);
                        }     
                    }
                }
            }
            if ($free_cl) {
                $this->info('Mendapatkan free 1 '.$value2->name.' karena promo Buy 1 Get 1 Caffe Latte ');           
            }
            // $this->info('Pembeli Mendapatkan free 1 '.$value2->name.' karena promo Buy 1 Get 1 Caffe Latte ');           
            $this->info('Total Pembelian : '.number_format($total,0,",",".")); 
        }else{
            $this->error('Tidak ditemukan product sama sekali!');
        }
        // print_r($data);
    }
}
