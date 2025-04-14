<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

//    public function servers()
//    {
//        return $this->belongsTo(Attribute::class, 'user_name');
//    }
//
//    public function codebooks()
//    {
////        dump($this->Attribute);
//        return $this->Attribute->codebooks()->orderBy('value')->get();
//    }
}
