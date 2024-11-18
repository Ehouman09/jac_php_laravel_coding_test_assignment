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
        Schema::create('books', function (Blueprint $table) {
            
            $table->uuid('id')->primary(); //use uuid instead of integer
            $table->uuid('user_id'); //link to user
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title');
            $table->string('author');
            $table->text('description')->nullable();
            $table->year('publication_year');
            $table->string('slug')->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();// Add soft deletes column to table in order to restore deleted data in case of accidental deletion
        
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
