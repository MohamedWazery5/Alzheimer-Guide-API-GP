<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_schedulers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('details')->nullable();
            $table->time("time");
            $table->integer("repeats_per_day");
            $table->boolean('status');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('repeat_typeID')->constrained('repeats_type')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId("patient_id")->constrained('patients')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_schedulers');
    }
};
