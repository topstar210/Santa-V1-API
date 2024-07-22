<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * User
     * 
     * Get User Uploaded By Module
     *
     * @return array Modules
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
