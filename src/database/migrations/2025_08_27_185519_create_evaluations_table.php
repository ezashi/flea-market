<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade'); // 評価者
            $table->foreignId('evaluated_id')->constrained('users')->onDelete('cascade'); // 評価される人
            $table->integer('rating')->comment('1-5の評価');
            $table->timestamps();

            // 同じ取引で同じ人が複数回評価できないようにする
            $table->unique(['item_id', 'evaluator_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
}