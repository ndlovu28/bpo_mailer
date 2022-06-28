<?php
namespace Ndlovu28\BpoMailer\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpoCustomer extends Model{
	use HasFactory;
	protected $guarded = [];

	protected $fillable = [
		'customer_code',
		'customer_id'
	];
}
?>