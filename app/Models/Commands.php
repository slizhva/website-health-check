<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commands extends Model
{
    use HasFactory;

    public const STATUS_SUCCESS = 1;
    public const STATUS_ERROR = 2;

    protected $table = 'commands';

    protected $fillable = [
        'link',
        'command',
        'type',
        'status',
    ];

    public function setCommandAttribute(array $commandData):string {
        return $this->attributes['command'] = json_encode($commandData);
    }

    public function getCommandAttribute():object {
        return json_decode($this->attributes['command'], false);
    }
}
