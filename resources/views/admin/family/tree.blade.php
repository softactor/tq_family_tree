@extends('adminlte::page')

@section('title', 'Family Tree')

@section('content_header')
    <h1>Family Tree Visualization</h1>
@endsection

@section('content')
<div id="familyTreeContainer" style="width: 100%; height: 800px; border: 1px solid #ddd;"></div>
@endsection

@section('js')
<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const treeData = @json($treeData); // Injected tree data into the script

        const width = document.getElementById("familyTreeContainer").offsetWidth;
        const height = 800;

        // Set up the canvas (SVG)
        const svg = d3.select("#familyTreeContainer")
            .append("svg")
            .attr("width", width)
            .attr("height", height)
            .call(d3.zoom().on("zoom", function (event) {
                svg.attr("transform", event.transform);
            }))
            .append("g")
            .attr("transform", "translate(50,50)");

        const treeLayout = d3.tree().size([height - 100, width - 200]);
        const root = d3.hierarchy(treeData);

        treeLayout(root);

        // Draw links (lines between nodes)
        svg.append("g")
            .selectAll("path")
            .data(root.links())
            .join("path")
            .attr("fill", "none")
            .attr("stroke", "#ccc")
            .attr("stroke-width", 2)
            .attr(
                "d",
                d3.linkHorizontal()
                    .x(d => d.y)
                    .y(d => d.x)
            );

        // Draw nodes (circles)
        const nodeGroup = svg.append("g")
            .selectAll("g")
            .data(root.descendants())
            .join("g")
            .attr("transform", d => `translate(${d.y},${d.x})`);

        nodeGroup.append("circle")
            .attr("r", 8)
            .attr("fill", d => (d.data.gender === "male" ? "#4287f5" : "#f5428e"));

        // Add node labels (name and date of birth)
        nodeGroup.append("text")
            .attr("x", 15)
            .attr("y", -10)
            .text(d => d.data.name)
            .style("font-size", "14px")
            .style("font-weight", "bold")
            .style("font-family", "Arial");

        nodeGroup.append("text")
            .attr("x", 15)
            .attr("y", 5)
            .text(d => `DOB: ${d.data.formatted_dob}`)
            .style("font-size", "12px")
            .style("font-family", "Arial")
            .style("fill", "#666");

        // Add events info
        nodeGroup.append("text")
            .attr("x", 15)
            .attr("y", 20)
            .text(d => {
                const events = d.data.events || [];
                if (events.length === 0) return '';
                
                // Show only first 2 events to avoid clutter
                const eventTypes = events.slice(0, 2).map(e => e.name).join(', ');
                return events.length > 2 ? `${eventTypes} +${events.length - 2} more` : eventTypes;
            })
            .style("font-size", "11px")
            .style("font-family", "Arial")
            .style("fill", "#28a745")
            .style("font-style", "italic");

        // Add hover tooltips with detailed event information
        nodeGroup.append("title")
            .text(d => {
                const events = d.data.events || [];
                let tooltip = `Name: ${d.data.name}\nGender: ${d.data.gender}\nDOB: ${d.data.formatted_dob}`;
                
                if (events.length > 0) {
                    tooltip += `\n\nEvents:`;
                    events.forEach(event => {
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