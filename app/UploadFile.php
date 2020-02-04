<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    protected $fillable = [
        'filename'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
