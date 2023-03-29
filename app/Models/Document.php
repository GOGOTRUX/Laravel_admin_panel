<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use Notifiable;
    use SoftDeletes;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ggt_op_document_details';
    protected $primaryKey = 'doc_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'doc_id','doc_driver_id', 'doc_number', 'doc_type_id', 'doc_veh_id', 'doc_user_id',
         'doc_images', 'is_deleted', 'doc_expiry', 
         'is_verified', 'verified_by', 'created_at', 
         'updated_at', 'deleted_at', 
    ];
}
