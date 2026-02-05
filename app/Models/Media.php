<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_id',
        'file_path',
        'type',
    ];

    // Relation: Media belongs to a node
    public function node()
    {
        return $this->belongsTo(Node::class);
    }
}