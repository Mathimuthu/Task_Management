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
            $table->string('title'); // Task Title
            $table->text('description')->nullable(); // Task Description
            $table->string('attachments')->nullable(); // File Attachments (comma-separated file paths)
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium'); // Priority Dropdown
            $table->date('date')->default(now()); // Date with default current date$table->date('assign_date'); // Assign Date
            $table->date('deadline')->nullable(); // Completion/Deadline Date
            $table->unsignedBigInteger('department_id'); // Assigned Department
            $table->unsignedBigInteger('role_id'); // Assigned Role in the Department
            $table->json('employee_ids'); // Assigned Employees (multiple selection)
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending'); // Task Status
            $table->timestamps(); // Created & Updated Time
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};