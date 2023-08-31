<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('exam_name', 250);
            $table->text('instruction')->nullable();
            $table->integer('exam_duration')->comment('Duration in minutes');
            $table->integer('no_of_attempts');
            $table->dateTime('exam_due_date');
            $table->dateTime('exam_start_date')->nullable();
            $table->dateTime('exam_end_date')->nullable();
            $table->unsignedInteger('result_display')->comment('1 = Automatic Evaluation; 2 = Teacher Evaluation');
            $table->unsignedInteger('is_published')->default(0)->comment('0=unpublished;1=published');
            $table->unsignedInteger('status')->default(1)->comment('0=inactive; 1=active');
            $table->unsignedInteger('exam_created_by')->comment('admin_user_id who create this exam');
            // Change created_at to use default current timestamp
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
