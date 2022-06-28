<?php
namespace Ndlovu28\BpoMailer\Console;

use Illuminate\Console\Command;

use DB;
use Schema;

use Ndlovu28\BpoMailer\Lib\BpoApi;

use Ndlovu28\BpoMailer\Models\BpoCustomer;
use Ndlovu28\BpoMailer\Models\BpoContact;


class SystemInit extends Command{
	protected $signature = 'bpomailer:install';
	protected $description = 'Install the Bpo Mailer package';

	public function handle(){
		$this->init();
	}

	function init(){
		$this->line("> Initialising the system");
		$this->line("> Checking environment varibales");
		$env_vars = [
			'APP_TEMPLATE',
			'BPO_VERSION',
			'BPO_URL',
			'BPO_USER',
			'BPO_PASS'
		];
		$bar = $this->output->createProgressBar(count($env_vars));
		foreach($env_vars AS $k=>$v){
			if(!env($v)){
				$this->error("Please set ".$v." varibale and value in your .env file");
				exit(0);
			}
			else{
				$bar->advance();
			}
		}
		$bar->finish();
		$this->newLine();
		$this->info("ENV variables passed");

		$this->line("> Cheching DB");
		try{
            DB::connection()->getPdo();
            $this->info("DB connection successful");
        }
        catch (\Exception $e) {
            $this->error("Database test failed. Please add you DB connection info in the .env file");
            return 1;
        }
        if(!Schema::hasTable('bpo_customers')){
            $this->line("> Creating databse migration");
            $this->call('migrate');
            $this->line("> Seedng table");
            $this->call('db:seed');
            $this->info("DB Tables Created");
        }
        else{
        	$this->info("DB Tables Okey");
        }
        $this->newLine();

        $bpo = new BpoApi();
        if($bpo->bpoStatus()){
        	$this->line("> Loading customers");
        	$customers = $bpo->getCustomerList();
        	if(is_array($customers)){
        		$bar = $this->output->createProgressBar(count($customers));
        		foreach($customers AS $customer){
        			$cs = BpoCustomer::where('customer_id', $customer['fldCustomerID'])->first();
        			if(!$cs){
	        			BpoCustomer::create([
	        				'customer_code' => $customer['fldCustomerCode'],
	        				'customer_id' => $customer['fldCustomerID'],
	        			]);
	        		}
        			$bar->advance();
        		}
        		$bar->finish();
        		$this->newLine();
        		$this->info('Customers loaded');
        	}
        	else{
        		$this->error('Failed to get customers');
        	}

        	$this->line("Loading contacts");
        	$customers = BpoCustomer::all();
        	if($customers->count() > 0){
        		$error = [];
        		$bar = $this->output->createProgressBar($customers->count());
	        	foreach($customers AS $customer){
	        		$contacts = $bpo->getContacts($customer->customer_id);
	        		if(is_array($contacts)){
	        			foreach($contacts AS $contact){
		        			$cnt = BpoContact::where('contact_id', $contact['fldContactID'])->first();
		        			if(!$cnt){
		        				BpoContact::create([
				        			'customer_code' => $customer->customer_code,
									'contact_id' => $contact['fldContactID'],
									'fname' => $contact['fldContactFirstName'],
									'lname' => $contact['fldContactSurname'],
									'phone' => $contact['fldContactPhoneNumber'],
									'email' => $contact['fldContactEmail'],
									'type' => $contact['fldContactType'],
									'type_description' => $contact['fldContactTypeDescription']
				        		]);
		        			}	
		        		}	
	        		}
	        		else{
	        			$error[] = $customer->customer_code;
	        		}
	        		$bar->advance();
	        	}
	        	$bar->finish();
	    		$this->newLine();
	    		if(count($error) > 0){
	    			$this->warn("Contacts not loaded on company codes bellow");
	    			foreach($error AS $er){
	    				$this->warn($er);
	    			}
	    		}
	    		else{
	    			$this->info("Loding contacts success");
	    		}	
        	}
        }

	}
}
?>