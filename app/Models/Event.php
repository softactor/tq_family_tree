<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_id',
        'event_name',
        'event_date',
        'description'
    ];

    public function node()
    {
        return $this->belongsTo(Node::class);
    }

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->event_date)->format('M d, Y');
    }

    public function getEventTypeAttribute()
    {
        return ucfirst($this->event_name);
    }
}