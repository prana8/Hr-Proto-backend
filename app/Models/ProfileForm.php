<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileForm extends Model
{
    use HasFactory;
    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'name',
        'username',
        'age',
        'email',
        'gender',
        'tanggal_lahir',
        'country',
        'profile_image',
        // 'image_path'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
