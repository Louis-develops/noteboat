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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_list_id')->constrained()->cascadeOnDelete();
            $table->string('title');

            // Enum to restrict possible values. Using Sqlite, which doesn't support native enum types, so we will use a string with a check constraint instead.
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            // Composite index here as we will be querying tasks by task_list_id and ordering by position frequently
            $table->index(['task_list_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
