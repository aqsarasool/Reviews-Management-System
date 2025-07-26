<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'rating',
        'review',
        'images',
        'status',
        'title',
        'product_name',
        'location',
        'review_date',
    ];
}
