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

    public function getErrorCommandAttribute():object|null|int {
        return Commands
            ::where('link', $this->id)
            ->where('type', Commands::STATUS_ERROR)
            ->limit(1)
            ->get('command')[0]->command ?? null;
    }

    public function setErrorCommandAttribute(array $command):void {
        Commands::updateOrCreate([
            'link' => $this->id,
            'type' => Commands::STATUS_ERROR
        ],[
            'command' => $command
        ]);
    }
}
