<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contratti', function (Blueprint $table) {
            $table->id();
            $table->string('username', 20)->charset('latin1')->collation('latin1_general_ci')->nullable(false);
            $table->date('data_hyrje')->nullable(false);
            $table->time('ora_hyrje')->nullable(false);
            $table->date('data_dalje')->nullable(false);
            $table->time('ora_dalje')->nullable(false);
            $table->integer('nr')->nullable(false);
            $table->time('last_check')->nullable(false);
            $table->string('nome', 32)->nullable(false);
            $table->string('cognome', 32)->nullable(false);
            $table->string('team', 32)->nullable(false);
            $table->string('campaign', 32)->nullable(false);
            $table->string('sede', 32)->nullable(false);
            $table->string('sede_checked', 32)->nullable(false);
            $table->string('tippo_carta', 32)->nullable(false);
            $table->string('mobile', 15)->nullable(false);
            $table->string('nr_carta', 10)->nullable(false);
            $table->string('check_manule', 2)->nullable(false)->default('No');
            $table->string('user_check', 20)->nullable(false);
            $table->string('ip_check', 20)->nullable(false);
            $table->string('check_vis_form_int', 1)->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');


            $table->index(['username', 'data_hyrje', 'nr']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratti');
    }
};
