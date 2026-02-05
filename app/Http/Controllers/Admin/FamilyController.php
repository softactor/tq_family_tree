<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Node;
use App\Models\Relationship;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    /**
     * Show all family members.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function members()
    {
        $familyMembers = Node::all(); // Get all nodes (family members)
        return view('admin.family.members', compact('familyMembers'));
    }

    /**
     * Show add family member form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function add()
    {
        return view('admin.family.add');
    }

    /**
     * Store a new family member.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
        ]);

        // Create a new family member (Node model)
        $member = Node::create($validated);

        // Return JSON response for AJAX
        return response()->json($member);
    }

    /**
     * Show the specified family member.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $member = Node::findOrFail($id);
        return response()->json($member);
    }

    /**
     * Update the specified family member.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
        ]);

        // Find and update the family member
        $member = Node::findOrFail($id);
        $member->update($validated);

        // Return JSON response for AJAX
        return response()->json($member);
    }

    /**
     * Remove the specified family member.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $member = Node::findOrFail($id);
        $member->delete();

        return response()->json(['success' => true]);
    }

    public function fetchEvents($id)
    {
        $events = Event::where('node_id', $id)->get();
        return response()->json($events);
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'node_id' => 'required|exists:nodes,id',
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $event = Event::create($validated);
        return response()->json($event);
    }

    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully!']);
    }

    public function fetchRelationships($id)
    {
        $relationships = Relationship::where('node1_id', $id)
            ->orWhere('node2_id', $id)
            ->with(['firstMember', 'secondMember'])
            ->get()
            ->map(function ($relation) use ($id) {
                $relatedMember = $relation->node1_id == $id
                    ? $relation->secondMember->first_name . ' ' . $relation->secondMember->last_name
                    : $relation->firstMember->first_name . ' ' . $relation->firstMember->last_name;

                return [
                    'id' => $relation->id,
                    'related_member' => $relatedMember,
                    'relationship_type' => ucfirst($relation->relationship_type),
                ];
            });

        return response()->json($relationships);
    }

    public function storeRelationship(Request $request)
    {
        $validated = $request->validate([
            'node1_id' => 'required|exists:nodes,id',
            'node2_id' => 'required|exists:nodes,id|different:node1_id', // Cannot relate with oneself
            'relationship_type' => 'required|in:parent,child,spouse,sibling',
        ]);

        // Create the relationship
        $relationship = Relationship::create($validated);
        $relatedMember = Node::find($validated['node2_id']);

        return response()->json([
            'id' => $relationship->id,
            'related_member' => $relatedMember->first_name . ' ' . $relatedMember->last_name,
            'relationship_type' => ucfirst($relationship->relationship_type),
        ]);
    }

    public function deleteRelationship($id)
    {
        $relationship = Relationship::findOrFail($id);
        $relationship->delete();

        return response()->json(['message' => 'Relationship deleted successfully!']);
    }
}