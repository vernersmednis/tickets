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
        
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // required title
            $table->text('description'); // required description
            $table->enum('priority', ['low', 'medium', 'high'])->default('low'); // priority
            $table->enum('status', ['open', 'closed'])->default('open'); // status
            $table->unsignedBigInteger('user_agent_id'); // foreign key to users table
            $table->timestamps();

            // Foreign key constraint for user agent
            $table->foreign('user_agent_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
