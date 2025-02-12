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
            $table->id(); // Первичный ключ
            $table->string('title'); // Название задачи
            $table->text('description')->nullable(); // Описание задачи (необязательно)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Создатель задачи
            $table->timestamps(); // Добавляет столбцы created_at и updated_at
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
