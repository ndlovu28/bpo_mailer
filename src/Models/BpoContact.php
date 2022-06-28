<?php
namespace Ndlovu28\BpoMailer\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpoContact extends Model{
	use HasFactory;
	protected $guarded = [];

	protected $fillable = [
		'customer_code',
		'contact_id',
		'fname',
		'lname',
		'phone',
		'email',
		'type',
		'type_description'
	];
}
?>