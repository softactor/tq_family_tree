@extends('adminlte::page')

@section('title', 'Family Tree - ' . $memberName)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Family Tree: {{ $memberName }}</h1>
        <a href="{{ route('admin.family.members') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Members
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Family Tree Visualization</h5>
            </div>
            <div class="card-body">
                <div id="memberTreeContainer" style="width: 100%; height: 700px; border: 1px solid #ddd;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Member Info Card -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Member Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{ $treeData['member']['name'] }}</h5>
                        <p><strong>Gender:</strong> {{ ucfirst($treeData['member']['gender']) }}</p>
                        <p><strong>Date of Birth:</strong> {{ $treeData['member']['formatted_dob'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Events</h5>
                        @if(count($treeData['member']['events']) > 0)
                            <ul class="list-unstyled">
                                @foreach($treeData['member']['events'] as $event)
                                <li>
                                    <strong>{{ $event['name'] }}:</strong> 
                                    {{ $event['formatted_date'] }}
                                    @if($event['description'])
                                        <br><small class="text-muted">{{ $event['description'] }}</small>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No events recorded</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const treeData = @json($treeData);
        
        // Create a hierarchical structure for D3
        const rootData = {
            name: treeData.member.name,
            gender: treeData.member.gender,
            dob: treeData.member.formatted_dob,
            events: treeData.member.events,
            isMain: true,
            children: []
        };

        // Add parents as ancestors
        if (treeData.parents.length > 0) {
            const parentNode = {
                name: 'Parents',
                isParentGroup: true,
                children: treeData.parents.map(parent => ({
                    name: parent.name,
                    gender: parent.gender,
                    dob: parent.formatted_dob,
                    events: parent.events,
                    isParent: true,
                    children: []
                }))
            };
            rootData.children.push(parentNode);
        }

        // Add spouse
        if (treeData.spouse) {
            const spouseNode = {
                name: treeData.spouse.name,
                gender: treeData.spouse.gender,
                dob: treeData.spouse.formatted_dob,
                events: treeData.spouse.events,
                isSpouse: true,
                children: []
            };
            rootData.children.push(spouseNode);
        }

        // Add children
        if (treeData.member.children.length > 0) {
            const childrenNode = {
                name: 'Children',
                isChildrenGroup: true,
                children: treeData.member.children.map(child => ({
                    name: child.name,
                    gender: child.gender,
                    dob: child.formatted_dob,
                    events: child.events,
                    isChild: true,
                    children: child.children.map(grandChild => ({
                        name: grandChild.name,
                        gender: grandChild.gender,
                        dob: grandChild.formatted_dob,
                        events: grandChild.events,
                        isGrandChild: true,
                        children: []
                    }))
                }))
            };
            rootData.children.push(childrenNode);
        }

        const width = document.getElementById("memberTreeContainer").offsetWidth;
        const height = 700;

        // Set up the canvas (SVG)
        const svg = d3.select("#memberTreeContainer")
            .append("svg")
            .attr("width", width)
            .attr("height", height)
            .call(d3.zoom().on("zoom", function (event) {
                svg.attr("transform", event.transform);
            }))
            .append("g")
            .attr("transform", "translate(50,50)");

        // Create a simple hierarchical layout
        const hierarchy = d3.hierarchy(rootData);
        const treeLayout = d3.tree().size([height - 100, width - 100]);
        const root = treeLayout(hierarchy);

        // Draw links (lines between nodes)
        svg.append("g")
            .selectAll("path")
            .data(root.links())
            .join("path")
            .attr("fill", "none")
            .attr("stroke", d => {
                // Different colors for different relationship types
                if (d.source.data.isMain) return "#007bff";
                if (d.source.data.isParentGroup || d.target.data.isParent) return "#28a745";
                if (d.source.data.isSpouse || d.target.data.isSpouse) return "#ff6b6b";
                if (d.source.data.isChildrenGroup || d.target.data.isChild) return "#6f42c1";
                if (d.target.data.isGrandChild) return "#fd7e14";
                return "#ccc";
            })
            .attr("stroke-width", 2)
            .attr("stroke-dasharray", d => {
                if (d.source.data.isParentGroup || d.source.data.isChildrenGroup) return "5,5";
                return "none";
            })
            .attr(
                "d",
                d3.linkHorizontal()
                    .x(d => d.y)
                    .y(d => d.x)
            );

        // Draw nodes
        const nodeGroup = svg.append("g")
            .selectAll("g")
            .data(root.descendants())
            .join("g")
            .attr("transform", d => `translate(${d.y},${d.x})`);

        // Draw node circles with different colors
        nodeGroup.append("circle")
            .attr("r", 10)
            .attr("fill", d => {
                if (d.data.isMain) return "#007bff"; // Blue for main member
                if (d.data.isParent) return "#28a745"; // Green for parents
                if (d.data.isSpouse) return "#ff6b6b"; // Red for spouse
                if (d.data.isChild) return "#6f42c1"; // Purple for children
                if (d.data.isGrandChild) return "#fd7e14"; // Orange for grandchildren
                if (d.data.isParentGroup || d.data.isChildrenGroup) return "#6c757d"; // Gray for groups
                return "#adb5bd"; // Default gray
            })
            .attr("stroke", "#fff")
            .attr("stroke-width", 2);

        // Add node labels
        nodeGroup.append("text")
            .attr("x", 15)
            .attr("y", -5)
            .text(d => {
                if (d.data.isParentGroup) return "Parents";
                if (d.data.isChildrenGroup) return "Children";
                return d.data.name;
            })
            .style("font-size", d => d.data.isMain ? "14px" : "12px")
            .style("font-weight", d => d.data.isMain ? "bold" : "normal")
            .style("font-family", "Arial");

        // Add date of birth
        nodeGroup.append("text")
            .attr("x", 15)
            .attr("y", 10)
            .text(d => d.data.dob ? `DOB: ${d.data.dob}` : '')
            .style("font-size", "11px")
            .style("font-family", "Arial")
            .style("fill", "#666");

        // Add hover tooltips
        nodeGroup.append("title")
            .text(d => {
                let tooltip = `Name: ${d.data.name}`;
                if (d.data.gender) tooltip += `\nGender: ${d.data.gender}`;
                if (d.data.dob) tooltip += `\nDOB: ${d.data.dob}`;
                
                if (d.data.events && d.data.events.length > 0) {
                    tooltip += `\n\nEvents:`;
                    d.data.events.forEach(event => {
                        tooltip += `\n- ${event.name}: ${event.formatted_date}`;
                        if (event.description) {
                            tooltip += ` (${event.description})`;
                        }
                    });
                }
                
                return tooltip;
            });
    });
</script>
@endsection

@section('css')
<style>
    .card {
        margin-bottom: 20px;
    }
    #memberTreeContainer {
        background-color: #f8f9fa;
        border-radius: 5px;
    }
</style>
@endsection