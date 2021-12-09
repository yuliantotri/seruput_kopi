<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->integer('price');
            $table->unsignedBigInteger('district_id');
            $table->foreign('district_id')->references('id')->on('districts')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        $prod['code'] = ['FR', 'CL', 'BC', 'KL'];
        $prod['name'] = ['Frappucino', 'Cafe Latte', 'Black Coffee', 'Kopi Luwak'];
        $prod['price'] = [4, 3, 2, 5, 5, 4, 3, 8, 3, 2, 1, 4, 4, 2, 1, 6];
        $counter = 0;
        for ($i=0; $i < 4; $i++) { 
            foreach ($prod['code'] as $key => $value) {
                $district[$counter]['code'] = $prod['code'][$key].($i+1);
                $district[$counter]['name'] = $prod['name'][$key];
                $district[$counter]['district_id'] = $i+1;
                $district[$counter]['price'] = $prod['price'][$counter]*10000;
                $district[$counter]['created_at'] = date('Y-m-d H:i:s');

                $counter += 1;
            }
        }
        DB::table('products')->insert($district);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
