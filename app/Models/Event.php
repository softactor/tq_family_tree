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
        'description',
    ];

    // Relation: Event belongs to a node
    public function node()
    {
        return $this->belongsTo(Node::class);
    }
}