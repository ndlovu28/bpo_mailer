<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
	/**
 	* * Run the migrations.
 	*
 	* @return void
 	* */
 	public function up(){
 		Schema::create('bpo_contacts', function (Blueprint $table){
 			$table->id();
 			$table->string('customer_code');
 			$table->string('contact_id');
 			$table->string('fname');
 			$table->string('lname');
 			$table->string('phone');
 			$table->string('email');
 			$table->string('type');
 			$table->string('type_description');
 			$table->timestamps();
 		});
    }

    /**
 	* Reverse the migrations.
 	*
 	* @return void
 	*/
    public function down(){
    	Schema::dropIfExists('bpo_contacts');
    }
};
?>