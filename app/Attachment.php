<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'report_attachment';
    protected $fillable = [
        'file', 'format', 'report_id'
    ];
}
