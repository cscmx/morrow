<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable; 

#[Fillable(['name', 'date_of_birth', 'category', 'color'])]
class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory;

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

}
