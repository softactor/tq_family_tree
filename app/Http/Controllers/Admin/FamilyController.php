<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Node;
use App\Models\Relationship;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FamilyController extends Controller
{
    /**
     * Show all family members page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function members()
    {
        return view('admin.family.members');
    }

    /**
     * Get family members for DataTables (server-side).
     */
    public function getMembers(Request $request)
{
    if ($request->ajax()) {
        $query = Node::query();

        // Get DataTables parameters
        $orderColumnIndex = $request->get('order')[0]['column'] ?? 3; // Default to DOB column (index 3)
        $orderDirection = $request->get('order')[0]['dir'] ?? 'asc';

        // Map DataTables columns to database columns
        $columnMap = [
            0 => 'id', // DT_RowIndex
            1 => 'first_name',
            2 => 'gender',
            3 => 'dob', // This is the DOB column
            4 => 'dob', // Age also uses dob
            5 => 'id', // Actions column
        ];

        $orderColumn = $columnMap[$orderColumnIndex] ?? 'dob';

        // Special handling for DOB column to put NULLs last
        if ($orderColumn == 'dob') {
            if ($orderDirection == 'asc') {
                $query->orderByRaw('dob IS NULL, dob ASC');
            } else {
                $query->orderByRaw('dob IS NOT NULL, dob DESC');
            }
        } else {
            // For other columns, normal sorting
            $query->orderBy($orderColumn, $orderDirection);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('full_name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            })
            ->addColumn('dob_formatted', function ($row) {
                if (!$row->dob) {
                    return 'N/A';
                }
                return \Carbon\Carbon::parse($row->dob)->format('M d, Y');
            })
            ->addColumn('age', function ($row) {
                if (!$row->dob) {
                    return 'N/A';
                }
                return \Carbon\Carbon::parse($row->dob)->age . ' years';
            })
            ->addColumn('gender_formatted', function ($row) {
                return ucfirst($row->gender);
            })
            ->addColumn('actions', function($row) {
                return '
                    <div class="btn-group" role="group">
                        <button class="btn btn-primary btn-sm view-tree-btn" 
                                data-id="'.$row->id.'" 
                                data-name="'.$row->first_name.' '.$row->last_name.'"
                                title="View Family Tree">
                            <i class="fas fa-project-diagram"></i>
                        </button>
                        <button class="btn btn-info btn-sm manage-relationships-btn" 
                                data-id="'.$row->id.'" 
                                data-name="'.$row->first_name.' '.$row->last_name.'"
                                title="Manage Relationships">
                            <i class="fas fa-users"></i>
                        </button>
                        <button class="btn btn-info btn-sm manage-events-btn" 
                                data-id="'.$row->id.'" 
                                data-name="'.$row->first_name.' '.$row->last_name.'"
                                title="Manage Events">
                            <i class="fas fa-calendar-alt"></i>
                        </button>
                        <button class="btn btn-warning btn-sm edit-btn" 
                                data-id="'.$row->id.'"
                                title="Edit Member">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" 
                                data-id="'.$row->id.'"
                                title="Delete Member">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['actions'])
            ->filter(function ($query) use ($request) {
                if (!empty($request->get('search')['value'])) {
                    $search = $request->get('search')['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%")
                            ->orWhere('gender', 'LIKE', "%{$search}%")
                            ->orWhere('dob', 'LIKE', "%{$search}%");
                    });
                }
            })
            ->setRowId(function ($row) {
                return 'member-row-' . $row->id;
            })
            ->make(true);
    }

    return abort(404);
}

    /**
     * Show add family member form.
     */
    public function add()
    {
        return view('admin.family.add');
    }

    /**
     * Store a new family member.
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
     */
    public function show($id)
    {
        $member = Node::findOrFail($id);
        return response()->json($member);
    }

    /**
     * Update the specified family member.
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
     */
    public function destroy($id)
    {
        $member = Node::findOrFail($id);
        $member->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Fetch events for a specific family member.
     */
    public function fetchEvents($id)
    {
        $events = Event::where('node_id', $id)->get();
        return response()->json($events);
    }

    /**
     * Store a new event.
     */
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

    /**
     * Delete an event.
     */
    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully!']);
    }

    /**
     * Fetch relationships for a specific family member.
     */
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

    /**
     * Store a new relationship.
     */
    public function storeRelationship(Request $request)
    {
        $validated = $request->validate([
            'node1_id' => 'required|exists:nodes,id',
            'node2_id' => 'required|exists:nodes,id|different:node1_id',
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

    /**
     * Delete a relationship.
     */
    public function deleteRelationship($id)
    {
        $relationship = Relationship::findOrFail($id);
        $relationship->delete();

        return response()->json(['message' => 'Relationship deleted successfully!']);
    }

    /**
     * Recursively build family tree.
     */
    private function buildTree($node)
    {
        // Get children nodes directly (not through relationships)
        $children = $node->children()->get();

        $childNodes = $children->map(function ($child) {
            return $this->buildTree($child);
        });

        return [
            'id' => $node->id,
            'name' => trim($node->first_name . ' ' . $node->last_name),
            'gender' => $node->gender,
            'dob' => $node->dob,
            'children' => $childNodes->toArray(),
        ];
    }

    /**
     * Alternative tree building method using eager loading for better performance.
     */
    public function fetchFamilyTree()
    {
        // Eager load all nodes with their children recursively
        $nodes = Node::with(['children' => function ($query) {
            $query->with(['children' => function ($query) {
                $query->with('children'); // 3 levels deep
            }]);
        }])->get();

        // Find root nodes (nodes without parents)
        $rootNodes = $nodes->filter(function ($node) use ($nodes) {
            // A node is a root if no other node has it as a child
            return !$nodes->contains(function ($n) use ($node) {
                return $n->children->contains('id', $node->id);
            });
        });

        if ($rootNodes->isEmpty()) {
            return response()->json(['error' => 'No root nodes found'], 404);
        }

        // Build tree from the first root node
        $tree = $this->formatTree($rootNodes->first());

        return response()->json($tree);
    }

    /**
     * Format tree for JSON response.
     */
    private function formatTree($node)
    {
        if (!$node) {
            return null;
        }

        $children = $node->children->map(function ($child) {
            return $this->formatTree($child);
        });

        return [
            'id' => $node->id,
            'name' => trim($node->first_name . ' ' . $node->last_name),
            'gender' => $node->gender,
            'dob' => $node->dob,
            'children' => $children->toArray(),
        ];
    }








    public function treeVisualization()
    {
        // Find root nodes (nodes without parents)
        $rootNodes = Node::whereDoesntHave('parents')
            ->with(['children' => function ($query) {
                $query->with(['children' => function ($query) {
                    $query->with(['children' => function ($query) {
                        $query->with(['allEvents', 'children']);
                    }, 'allEvents']);
                }, 'allEvents']);
            }, 'allEvents'])
            ->get();

        if ($rootNodes->isEmpty()) {
            // If no root nodes found, use the oldest person as root
            $rootNode = Node::whereNotNull('dob')
                ->with(['children' => function ($query) {
                    $query->with(['children' => function ($query) {
                        $query->with(['children' => function ($query) {
                            $query->with(['allEvents', 'children']);
                        }, 'allEvents']);
                    }, 'allEvents']);
                }, 'allEvents'])
                ->orderBy('dob', 'asc')
                ->first();

            if (!$rootNode) {
                $rootNode = Node::with(['children' => function ($query) {
                    $query->with(['children' => function ($query) {
                        $query->with(['children' => function ($query) {
                            $query->with(['allEvents', 'children']);
                        }, 'allEvents']);
                    }, 'allEvents']);
                }, 'allEvents'])->first();
            }

            if (!$rootNode) {
                return abort(404, "No family members found.");
            }

            $tree = $this->buildTreeWithEvents($rootNode);
        } else {
            // Build tree for the first root node
            $tree = $this->buildTreeWithEvents($rootNodes->first());
        }

        return view('admin.family.tree')->with('treeData', $tree);
    }

    /**
     * Recursively build family tree with events.
     */
    private function buildTreeWithEvents($node)
    {
        // Get children nodes
        $children = $node->children()->with('allEvents')->get();

        $childNodes = $children->map(function ($child) {
            return $this->buildTreeWithEvents($child);
        });

        // Get events for this node
        $events = $node->allEvents->map(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->event_name,
                'date' => $event->event_date,
                'formatted_date' => \Carbon\Carbon::parse($event->event_date)->format('M d, Y'),
                'description' => $event->description,
            ];
        });

        return [
            'id' => $node->id,
            'name' => trim($node->first_name . ' ' . $node->last_name),
            'gender' => $node->gender,
            'dob' => $node->dob,
            'formatted_dob' => $node->dob ? \Carbon\Carbon::parse($node->dob)->format('M d, Y') : 'N/A',
            'events' => $events->toArray(),
            'children' => $childNodes->toArray(),
        ];
    }







    /**
     * Show family tree for a specific member.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function viewMemberTree($id)
    {
        $member = Node::with([
            'allEvents',
            'children' => function ($query) {
                $query->with([
                    'allEvents',
                    'children' => function ($query) {
                        $query->with([
                            'allEvents',
                            'children' => function ($query) {
                                $query->with(['allEvents', 'children']);
                            }
                        ]);
                    }
                ]);
            }
        ])->findOrFail($id);

        // Also get parents to show upward relationships
        $parents = $member->parents()->with('allEvents')->get();
        $spouse = $member->spouse()->with('allEvents')->first();

        // Build the tree with the member as the root
        $tree = $this->buildMemberTree($member, $parents, $spouse);

        return view('admin.family.member-tree')
            ->with('treeData', $tree)
            ->with('memberName', $member->first_name . ' ' . $member->last_name);
    }

    /**
     * Build tree for a specific member.
     */
    private function buildMemberTree($member, $parents, $spouse)
    {
        // Get children nodes
        $children = $member->children()->with('allEvents')->get();

        $childNodes = $children->map(function ($child) {
            $grandChildren = $child->children()->with('allEvents')->get();

            $grandChildNodes = $grandChildren->map(function ($grandChild) {
                return [
                    'id' => $grandChild->id,
                    'name' => trim($grandChild->first_name . ' ' . $grandChild->last_name),
                    'gender' => $grandChild->gender,
                    'dob' => $grandChild->dob,
                    'formatted_dob' => $grandChild->dob ? \Carbon\Carbon::parse($grandChild->dob)->format('M d, Y') : 'N/A',
                    'events' => $grandChild->allEvents->map(function ($event) {
                        return [
                            'id' => $event->id,
                            'name' => $event->event_name,
                            'date' => $event->event_date,
                            'formatted_date' => \Carbon\Carbon::parse($event->event_date)->format('M d, Y'),
                            'description' => $event->description,
                        ];
                    })->toArray(),
                    'children' => [],
                ];
            });

            return [
                'id' => $child->id,
                'name' => trim($child->first_name . ' ' . $child->last_name),
                'gender' => $child->gender,
                'dob' => $child->dob,
                'formatted_dob' => $child->dob ? \Carbon\Carbon::parse($child->dob)->format('M d, Y') : 'N/A',
                'events' => $child->allEvents->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'name' => $event->event_name,
                        'date' => $event->event_date,
                        'formatted_date' => \Carbon\Carbon::parse($event->event_date)->format('M d, Y'),
                        'description' => $event->description,
                    ];
                })->toArray(),
                'children' => $grandChildNodes->toArray(),
            ];
        });

        // Get events for the main member
        $events = $member->allEvents->map(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->event_name,
                'date' => $event->event_date,
                'formatted_date' => \Carbon\Carbon::parse($event->event_date)->format('M d, Y'),
                'description' => $event->description,
            ];
        });

        // Prepare parent nodes
        $parentNodes = $parents->map(function ($parent) {
            return [
                'id' => $parent->id,
                'name' => trim($parent->first_name . ' ' . $parent->last_name),
                'gender' => $parent->gender,
                'dob' => $parent->dob,
                'formatted_dob' => $parent->dob ? \Carbon\Carbon::parse($parent->dob)->format('M d, Y') : 'N/A',
                'events' => $parent->allEvents->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'name' => $event->event_name,
                        'date' => $event->event_date,
                        'formatted_date' => \Carbon\Carbon::parse($event->event_date)->format('M d, Y'),
                        'description' => $event->description,
                    ];
                })->toArray(),
                'children' => [],
            ];
        });

        // Prepare spouse node if exists
        $spouseNode = null;
        if ($spouse) {
            $spouseNode = [
                'id' => $spouse->id,
                'name' => trim($spouse->first_name . ' ' . $spouse->last_name),
                'gender' => $spouse->gender,
                'dob' => $spouse->dob,
                'formatted_dob' => $spouse->dob ? \Carbon\Carbon::parse($spouse->dob)->format('M d, Y') : 'N/A',
                'events' => $spouse->allEvents->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'name' => $event->event_name,
                        'date' => $event->event_date,
                        'formatted_date' => \Carbon\Carbon::parse($event->event_date)->format('M d, Y'),
                        'description' => $event->description,
                    ];
                })->toArray(),
                'children' => [],
                'isSpouse' => true,
            ];
        }

        return [
            'member' => [
                'id' => $member->id,
                'name' => trim($member->first_name . ' ' . $member->last_name),
                'gender' => $member->gender,
                'dob' => $member->dob,
                'formatted_dob' => $member->dob ? \Carbon\Carbon::parse($member->dob)->format('M d, Y') : 'N/A',
                'events' => $events->toArray(),
                'children' => $childNodes->toArray(),
            ],
            'parents' => $parentNodes->toArray(),
            'spouse' => $spouseNode,
        ];
    }
}
