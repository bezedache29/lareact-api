<?php

namespace App\Models;

use App\Models\User;
use App\Models\Picture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['picture_id', 'user_id'];

    protected $table = 'picture_user';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }
}
