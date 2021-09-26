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
            $table->integer('Pidx')->autoIncrement();
            $table->string('name',45);
            $table->string('selectedBrand');
            $table->string('selectedCategories');
            $table->string('state');
            $table->integer('price');
            $table->integer('sales');
            $table->string('extension',45);
            $table->timestamp('created_at')->useCurrent();
            $table->softDeletes('deleted_at',0);

            // $table->foreign('Bidx')->references('Bidx')->on('brands');
            // $table->foreign('Cidx')->references('Cidx')->on('categories');
        });
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
