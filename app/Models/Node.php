<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'dod',
        'profile_photo',
    ];

    // Relation: A node belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation: A node has many relationships where it's node1
    public function relationshipsAsNode1()
    {
        return $this->hasMany(Relationship::class, 'node1_id');
    }

    // Relation: A node has many relationships where it's node2
    public function relationshipsAsNode2()
    {
        return $this->hasMany(Relationship::class, 'node2_id');
    }

    // Relation: A node has many events
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Get all outgoing relationships
    public function outgoingRelationships()
    {
        return $this->hasMany(Relationship::class, 'node1_id');
    }

    // Get all incoming relationships
    public function incomingRelationships()
    {
        return $this->hasMany(Relationship::class, 'node2_id');
    }

    /**
     * Get the children of a node (where this node is the parent).
     * In your relationships table: node1_id = parent, node2_id = child, relationship_type = 'parent'
     */
    public function children()
    {
        return $this->belongsToMany(
            Node::class,
            'relationships',
            'node1_id', // This node is the parent (node1_id)
            'node2_id', // The child is node2_id
            'id',       // Local key on nodes table
            'id'        // Foreign key on nodes table
        )->wherePivot('relationship_type', 'parent')
            ->withPivot('relationship_type', 'created_at', 'updated_at')
            ->withTimestamps();
    }

    /**
     * Get the parents of a node (where this node is the child).
     * In your relationships table: node1_id = parent, node2_id = child, relationship_type = 'parent'
     */
    public function parents()
    {
        return $this->belongsToMany(
            Node::class,
            'relationships',
            'node2_id', // This node is the child (node2_id)
            'node1_id', // The parent is node1_id
            'id',       // Local key on nodes table
            'id'        // Foreign key on nodes table
        )->wherePivot('relationship_type', 'parent')
            ->withPivot('relationship_type', 'created_at', 'updated_at')
            ->withTimestamps();
    }

    /**
     * Get the spouse of a node.
     */
    public function spouse()
    {
        return $this->belongsToMany(
            Node::class,
            'relationships',
            'node1_id', // First spouse
            'node2_id', // Second spouse
            'id',
            'id'
        )->wherePivot('relationship_type', 'spouse')
            ->withPivot('relationship_type', 'created_at', 'updated_at')
            ->withTimestamps();
    }

    /**
     * Get siblings of a node.
     */
    public function siblings()
    {
        // Get all nodes that share the same parents
        return Node::whereHas('parents', function ($query) {
            $query->whereIn('node1_id', function ($subquery) {
                $subquery->select('node1_id')
                    ->from('relationships')
                    ->where('node2_id', $this->id)
                    ->where('relationship_type', 'parent');
            });
        })->where('id', '!=', $this->id);
    }

    /**
     * Get formatted name.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get formatted date of birth.
     */
    public function getFormattedDobAttribute()
    {
        if (!$this->dob) {
            return 'N/A';
        }
        return \Carbon\Carbon::parse($this->dob)->format('M d, Y');
    }

    /**
     * Get age.
     */
    public function getAgeAttribute()
    {
        if (!$this->dob) {
            return 'N/A';
        }
        return \Carbon\Carbon::parse($this->dob)->age . ' years';
    }

    /**
     * Get formatted gender.
     */
    public function getFormattedGenderAttribute()
    {
        return ucfirst($this->gender);
    }

    /**
     * Check if node has children.
     */
    public function hasChildren()
    {
        return $this->children()->exists();
    }

    /**
     * Check if node has parents.
     */
    public function hasParents()
    {
        return $this->parents()->exists();
    }

    /**
     * Check if node has spouse.
     */
    public function hasSpouse()
    {
        return $this->spouse()->exists();
    }

    /**
     * Get all relationships for this node.
     */
    public function allRelationships()
    {
        return Relationship::where('node1_id', $this->id)
            ->orWhere('node2_id', $this->id)
            ->get();
    }

    /**
     * Get all events for this node.
     */
    public function allEvents()
    {
        return $this->hasMany(Event::class)->orderBy('event_date', 'asc');
    }

    /**
     * Get birth event.
     */
    public function birthEvent()
    {
        return $this->hasOne(Event::class)->where('event_name', 'Birth');
    }

    /**
     * Get marriage events.
     */
    public function marriageEvents()
    {
        return $this->hasMany(Event::class)->where('event_name', 'Marriage');
    }

    /**
     * Get death event.
     */
    public function deathEvent()
    {
        return $this->hasOne(Event::class)->where('event_name', 'Death');
    }
}
