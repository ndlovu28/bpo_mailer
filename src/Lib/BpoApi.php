<?php
namespace Ndlovu28\BpoMailer\Lib;

use Log;

class BpoApi{
	public $bpo_url, $username, $password, $token, $bpo_version;

	function __construct(){
		$this->bpo_url = env('BPO_URL', 'http://41.160.190.205:50000/bpowebportal/BPOWebPortal.svc');
		$this->username = env('BPO_USER', 'ThinkT');
		$this->password = env('BPO_PASS');
		$this->bpo_version = env('BPO_VERSION', 'Legacy');

		$this->connect();
	}

	function bpoStatus(){
		if($this->token){
			return true;
		}
		return false;
	}

	function getContactTypes(){
		$payload = '';
		if($this->bpo_version == "Legacy"){
			$url = $url = $this->bpo_url.'/GetContactTypes';
			$payload = '{"UserName":"'.$this->username.'","Password":"'.$this->token.'", "GetMappedData": "false"}';
		}
		else{
			$url = $this->bpo_url.'/lists/contact/types';
		}
		$res = $this->runCurl($url, $payload);
		if($res){
			$data = $res["data"];
			if(count($data) > 0){
				return $res["data"];	
			}
			else{
				return false;
			}	
		}
	}

	function getCustomerList(){
		$payload = '';
		if($this->bpo_version == "Legacy"){
			$url = $url = $this->bpo_url.'/GetCustomerList';
			$payload = '{"UserName":"'.$this->username.'","Password":"'.$this->token.'", "SearchFilter":"0", "GetMappedData": "false"}';
		}
		else{
			$url = $this->bpo_url.'/customers/search/0';
		}
		$res = $this->runCurl($url, $payload);
		if($res){
			$data = $res["data"];
			if(count($data) > 0){
				return $res["data"];	
			}
			else{
				return false;
			}	
		}
	}

	function getContacts($company_id){
		$payload = '';
		if($this->bpo_version == "Legacy"){
			$url = $url = $this->bpo_url.'/GetCustomerContacts';
			$payload = '{"UserName":"'.$this->username.'","Password":"'.$this->token.'", "CustomerID":"'.$company_id.'", "GetMappedData": "false"}';
		}
		else{
			$url = $this->bpo_url.'customers/'.$company_id.'/contacts';
		}
		$res = $this->runCurl($url, $payload);
		if($res){
			$data = $res["data"];
			if(count($data) > 0){
				return $res["data"];	
			}
			else{
				return false;
			}	
		}
	}

	function connect(){
		$payload = '';
		if($this->bpo_version == "Legacy"){
			$url = $this->bpo_url.'/Login';
			$payload = '{"UserName":"'.$this->username.'","Password":"'.$this->password.'"}';	
		}
		else{
			$url = $this->bpo_url.'/login';
			$url = $url.'?UserName='.$this->user_name.'&Password='.$this->password;
		}

		$res = $this->runCurl($url, $payload);
		if($res){
			$this->token = $res["passToken"];
		}
		else{
			Session::flash("BPO ERROR: Faled to connect to BPO");
		}
	}

	function runCurl($url, $payload){
		$ch = curl_init($url);
		if($this->bpoStatus()){
			curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->token);  
		}
		if($this->bpo_version !== "Legacy"){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		}
		else{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$result = curl_exec($ch);
		$res = json_decode($result, true);
		curl_close($ch);
		
		if(isset($res["result"])){
			if($res["result"] == "success"){
				return $res;
			}
			else{
				$msg = '';
				if(isset($res['errorMessage'])){
					$msg = $res['errorMessage'];
				}
				if(isset($res['Message'])){
					$msg = $res['Message'];
				}
				session()->flash('error', $msg);	
				Log::error("BPO ERROR: ".$msg);
				return false;
			}
		}
		else{
			$msg = '';
			if(isset($res['errorMessage'])){
				$msg = $res['errorMessage'];
			}
			if(isset($res['Message'])){
				$msg = $res['Message'];
			}
			Log::error("BPO ERROR: ".$msg);
			session()->flash('error', 'BPO ERROR: '.$msg);
			return false;	
		}
	}
}
?>