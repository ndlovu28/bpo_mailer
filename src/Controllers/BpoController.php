<?php
namespace Ndlovu28\BpoMailer\Controllers;

use Request;
use Validator;
use Mail;

use Ndlovu28\BpoMailer\Mailer;
use Ndlovu28\BpoMailer\Lib\BpoApi;

use Ndlovu28\BpoMailer\Models\BpoCustomer;
use Ndlovu28\BpoMailer\Models\BpoContact;

class BpoController{
	public function showSendMail(){
		$template = env('APP_TEMPLATE', 'app');

		$types = [];

		$bpo = new BpoApi();
		if($bpo->bpoStatus()){
			$types = $bpo->getContactTypes();
		}

		return view('ndlovu28::create', compact('template', 'types'));
	}

	public function sendMail(){
		$valid = Validator::make(Request::all(), [
			'recipients' => 'required',
			'image' => 'required|file'
		]);
		if($valid->fails()){
			return back()->withErrors($valid)->withInput();
		}
		
		if(in_array('all', Request::input('recipients'))){
			$users = BpoContact::distinct()->get(['fname', 'lname', 'email']);
		}
		else{
			$users = BpoContact::whereIn('type', Request::input('recipients'))->distinct()->get(['fname', 'lname', 'email']);	
		}
		
		// $usr = [];
		// foreach($users AS $user){
		// 	$usr[] = $user->email;
		// 	$data
		// }

		$data = [
			'name' => "Wilson Ndlovu",
			'email' => "catrien@thinktank.co.za",
			'image' => ""
		];
		Mail::send('ndlovu28::mail', $data, function($message) use($data){
			$message->to($data['email'], $data['name'])->subject('Taropa Newsletter');
			$message->from('info@taropa.co.za','Taropa');
		});
	}
}