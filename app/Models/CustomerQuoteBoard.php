<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerQuoteBoard extends Model
{
    protected $table = 'ggt_customer_quote_board';
	protected $fillable = [
		'admin_id', 
		'quote_board_text', 
		'created_by', 
	];
}
