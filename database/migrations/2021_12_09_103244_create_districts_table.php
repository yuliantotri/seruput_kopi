<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $temp = ['P. Jawa', 'Bali', 'P. Sumatra', 'P. Kalimantan'];
        foreach ($temp as $key => $value) {
            $district[$key]['name'] = $value;
            $district[$key]['created_at'] = date('Y-m-d H:i:s');
        }
        DB::table('districts')->insert($district);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
}
