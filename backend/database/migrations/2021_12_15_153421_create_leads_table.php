<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('form');
            $table->string('services_ids', 20);
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('phone');
            $table->string('municipality');
            $table->string('province', 2);
            $table->string('region');
            $table->decimal('price',8,2);
            $table->text('description')->nullable();
            $table->boolean('phone_verified')->nullable();  // ->default(0);
            $table->boolean('approved')->default(0);
            $table->integer('sales_counter')->default(0);
            $table->text('notes')->nullable();
            $table->boolean('fake_lead')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
