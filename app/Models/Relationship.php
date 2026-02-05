<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'node1_id',
        'node2_id',
        'relationship_type',
    ];

    // Relation: A relationship belongs to the first node
    public function node1()
    {
        return $this->belongsTo(Node::class, 'node1_id');
    }

    // Relation: A relationship belongs to the second node
    public function node2()
    {
        return $this->belongsTo(Node::class, 'node2_id');
    }

    // Relationship: A relationship belongs to two family members
    public function firstMember()
    {
        return $this->belongsTo(Node::class, 'node1_id');
    }

    public function secondMember()
    {
        return $this->belongsTo(Node::class, 'node2_id');
    }
}