<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'category_id'
    ];
    protected $casts = [
        'id' => 'string'
    ];

    public function category()
    {
        return $this->hasOne(Category::class);
    }
}
