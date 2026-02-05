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
        'relationship_type'
    ];

    // Relationship with the first node
    public function firstMember()
    {
        return $this->belongsTo(Node::class, 'node1_id');
    }

    // Relationship with the second node
    public function secondMember()
    {
        return $this->belongsTo(Node::class, 'node2_id');
    }

    // Alias for parent node (when relationship_type = 'parent', node1_id is parent)
    public function parentNode()
    {
        return $this->belongsTo(Node::class, 'node1_id');
    }

    // Alias for child node (when relationship_type = 'parent', node2_id is child)
    public function childNode()
    {
        return $this->belongsTo(Node::class, 'node2_id');
    }

    // Alias for spouse1 (when relationship_type = 'spouse', node1_id is first spouse)
    public function spouse1()
    {
        return $this->belongsTo(Node::class, 'node1_id');
    }

    // Alias for spouse2 (when relationship_type = 'spouse', node2_id is second spouse)
    public function spouse2()
    {
        return $this->belongsTo(Node::class, 'node2_id');
    }

    /**
     * Get formatted relationship type.
     */
    public function getFormattedTypeAttribute()
    {
        return ucfirst($this->relationship_type);
    }

    /**
     * Get the other node in the relationship.
     */
    public function getOtherNode($currentNodeId)
    {
        if ($this->node1_id == $currentNodeId) {
            return $this->secondMember;
        } else {
            return $this->firstMember;
        }
    }

    /**
     * Check if this is a parent-child relationship.
     */
    public function isParentChild()
    {
        return $this->relationship_type === 'parent';
    }

    /**
     * Check if this is a spouse relationship.
     */
    public function isSpouse()
    {
        return $this->relationship_type === 'spouse';
    }

    /**
     * Check if this is a sibling relationship.
     */
    public function isSibling()
    {
        return $this->relationship_type === 'sibling';
    }
}