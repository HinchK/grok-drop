<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataUpload extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'site',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
