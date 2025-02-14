<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    protected $fillable = [
        'contact_id', 'email'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
