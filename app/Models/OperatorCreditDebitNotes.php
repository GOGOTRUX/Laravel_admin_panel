<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorCreditDebitNotes extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'ggt_op_credit_debit_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        
     'id', 'note_type', 'op_uid', 'op_mobile_no', 'amount', 'reason', 'reference', 'status', 'is_approved', 'approval_date', 'approved_by', 'is_deleted', 'created_at', 'updated_at','deleted_at', 'created_by', 'party_id', 'transaction_id', 'created_admin_id'
    ];
}
