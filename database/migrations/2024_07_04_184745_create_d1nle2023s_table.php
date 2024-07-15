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
        Schema::create('d1nle2023s', function (Blueprint $table) {
            $table->id();
            $table->string('Name')->nullable();
            $table->string('District')->nullable();
            $table->string('Province')->nullable();
            $table->string('Municipality')->nullable();
            $table->string('Barangay')->nullable();
            $table->string('Precinct_no')->nullable();
            $table->integer('_Orig_num')->nullable();
            $table->string('SIP')->nullable();
            $table->string('occupation')->nullable();
            $table->string('purok_rv')->nullable();
            $table->tinyInteger('survey_stat')->default(0);
            $table->text('remarks')->nullable();
            $table->string('HL')->nullable();
            $table->string('PL')->nullable();
            $table->string('goffice')->nullable();
            $table->string('gcoordinator')->nullable();
            $table->tinyInteger('man_add')->default(0);
            $table->string('grant_rv')->nullable();
            $table->string('gdate')->nullable();
            $table->string('gamount')->nullable();
            $table->string('gremarks')->nullable();
            $table->string('vstatus')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('dob')->nullable();
            $table->string('hlids')->nullable();
            $table->string('plids')->nullable();
            $table->string('userid')->nullable();
            $table->string('user')->nullable();
            $table->date('userlogs')->nullable();
            $table->string('pop')->nullable();
            $table->string('sethl')->nullable();
            $table->string('coord_lat')->nullable();
            $table->string('coord_long')->nullable();
            $table->string('qrcode_id')->nullable();
            $table->tinyInteger('isScannedQrCodeId')->default(0);
            $table->tinyInteger('isSent')->default(0);
            $table->tinyInteger('isToBeUploaded')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d1nle2023s');
    }
};
