<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operator_details', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedBigInteger('user_id');
            $table->string('phone', 20);
            $table->string('areas', 300);
            // $table->boolean('is_client')->default(0);
            $table->string('payer_id', 20)->nullable();
            $table->string('payer_firstname', 50)->nullable();
            $table->string('payer_lastname', 50)->nullable();
            $table->string('payer_phone', 30)->nullable();
            $table->string('payer_email', 60)->nullable();
            $table->string('invoice_company_name', 200)->nullable();
            $table->string('invoice_address', 200)->nullable();
            $table->string('invoice_cap', 5)->nullable();
            $table->string('invoice_city', 100)->nullable();
            $table->string('invoice_prov', 2)->nullable();
            $table->string('invoice_fiscal_code', 25)->nullable();
            $table->string('invoice_vat', 15)->nullable();
            $table->string('invoice_SDI', 10)->nullable();
            $table->string('notes', 500)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operator_details');
    }
}
