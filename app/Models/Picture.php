<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Picture extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image'];

    protected $with = ['user'];

    public static function boot()
    {
        parent::boot();

        // Pendant la création d'une image
        // On associe a l'image le user_id
        self::creating(function ($picture) {
            $picture->user()->associate(auth()->user()->id);
        });
    }

    public function search(String $search)
    {
        return $this->where('title', 'like', '%' . $search . '%');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
