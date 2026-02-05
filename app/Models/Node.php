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

    // Relation: A node has many relationships
    public function relationships()
    {
        return $this->hasMany(Relationship::class);
    }

    // Relation: A node has many events
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}