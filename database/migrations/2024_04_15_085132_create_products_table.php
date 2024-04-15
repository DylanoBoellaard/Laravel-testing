<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create table products
        Schema::dropIfExists('products');
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->decimal('price', 5,2);
            $table->timestamps();
        });

        // Insert values in table products
        // DB::table('products')->insert([
        //     [
        //         'name' => 'Mintnopjes',
        //         'price' => 5.00
        //     ],
        //     [
        //         'name' => 'Schoolkrijt',
        //         'price' => 7.50
        //     ],
        //     [
        //         'name' => 'Honingdrop',
        //         'price' => 6.90
        //     ],
        //     [
        //         'name' => 'Zure Beren',
        //         'price' => 3.20
        //     ],
        //     [
        //         'name' => 'Cola Flesjes',
        //         'price' => 2.15
        //     ],
        //     [
        //         'name' => 'Turtles',
        //         'price' => 1.05
        //     ],
        //     [
        //         'name' => 'Witte Muizen',
        //         'price' => 4.20
        //     ],
        //     [
        //         'name' => 'Reuze Slangen',
        //         'price' => 1.35
        //     ],
        //     [
        //         'name' => 'Zoute Rijen',
        //         'price' => 2.00
        //     ],
        //     [
        //         'name' => 'Winegums',
        //         'price' => 10.50
        //     ],
        //     [
        //         'name' => 'Drop Munten',
        //         'price' => 4.75
        //     ],
        //     [
        //         'name' => 'Kruis drop',
        //         'price' => 2.95
        //     ],
        //     [
        //         'name' => 'Zoute Ruitjes',
        //         'price' => 3.50
        //     ],
        // ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
