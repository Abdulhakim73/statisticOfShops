<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('branch_images', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            $table->string('path');
            $table->string('mime_type');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('branch_images');
    }
};
