<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    use HasFactory;

    const STATUS_AVAILABLE = 1;
    const STATUS_UNAVAILABLE = 0;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'links';

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'user',
        'link',
        'success_content',
        'status',
    ];
}
