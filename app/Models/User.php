<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
      use HasFactory, HasApiTokens;

    protected $fillable = ['username', 'password'];

   
    protected $hidden = [
        'password',
    ];
}
