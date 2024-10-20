<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedbacks extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'name',
        'information',
        'attempted_violation',
        'contact_number',
    ];
}
