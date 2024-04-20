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
        Schema::create('contacts_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('contact_id')->unsigned();
            $table->foreign('contact_id')
                ->references('id')
                ->on('contacts');
            $table->string('cep', 8)->nullable(false);
            $table->string('state', 100)->nullable(false);
            $table->string('city', 100)->nullable(false);
            $table->string('street', 255)->nullable(false);
            $table->integer('number')->nullable(false);
            $table->text('complement')->nullable();
            $table->double('latitude')->nullable(false);
            $table->double('longitude')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts_addresses');
    }
};
