<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('process_steps', function (Blueprint $table) {
            $table->id();
            $table->string('step_number');
            $table->string('title');
            $table->string('image');
            $table->text('description');
            $table->timestamps();
        });

        DB::table('process_steps')->insert([
            [
                'step_number' => '01',
                'title' => 'Place a phone call',
                'image' => '/photo/process_step1.png',
                'description' => 'Our professional customer care team is here to provide all the basic information you need to know.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'step_number' => '02',
                'title' => 'Visit',
                'image' => '/photo/process_step2.png',
                'description' => 'After a successful visit, considering all your requirements along with material we will provide an idea about cost.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'step_number' => '03',
                'title' => 'Design',
                'image' => '/photo/process_step3.png',
                'description' => 'For design you can bring your ideas to the table, or can choose from our vast collections. Else our creative designer team can make a complete design for you.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'step_number' => '04',
                'title' => 'Approval',
                'image' => '/photo/process_step4.png',
                'description' => 'After approval of the design we will provide final costing for the project considering your choice of material.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'step_number' => '05',
                'title' => 'Payment procedure',
                'image' => '/photo/process_step5.png',
                'description' => 'If the design and price is being finalized we will come up with a very easy and convenient payment procedure and working schedule.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'step_number' => '06',
                'title' => 'Agreement',
                'image' => '/photo/process_step6.png',
                'description' => 'Both parties will sign in an agreement. We believe in professionalism and commitment. Our professional architects, workers work simultaneously.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_steps');
    }
};
