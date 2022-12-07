<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_AVAILABLE = 1;
    public const STATUS_UNAVAILABLE = 2;

    public const STATUS_LABEL = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_AVAILABLE => 'Available',
        self::STATUS_UNAVAILABLE => 'Unavailable',
    ];

    protected $table = 'links';

    protected $fillable = [
        'user',
        'link',
        'success_content',
        'status',
    ];

    public function getErrorCommandAttribute() {
        return Commands::where('link', $this->id)->limit(1)->get('command')[0]->command;
    }
}
