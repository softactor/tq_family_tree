@extends('adminlte::page')

@section('title', 'Family Management')

@section('content_header')
    <h1>Family Management</h1>
@endsection

@section('content')
<div class="row">
    <!-- Add/Edit Family Member Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title" id="formTitle">Add Family Member</h5>
            </div>
            <div class="card-body">
                <form id="familyMemberForm">
                    <!-- CSRF Token -->
                    @csrf
                    <input type="hidden" name="id" id="member_id">
                    <input type="hidden" name="_method" id="form_method" value="POST">
                    
                    <div class="form-group">
                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter first name">
                        <div class="invalid-feedback" id="first_name_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter last name">
                        <div class="invalid-feedback" id="last_name_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender <span class="text-danger">*</span></label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        <div class="invalid-feedback" id="gender_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-4">
                                <select name="dob_day" id="dob_day" class="form-control">
                                    <option value="">Day</option>
                                    @for($i = 1; $i <= 31; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-4">
                                <select name="dob_month" id="dob_month" class="form-control">
                                    <option value="">Month</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <select name="dob_year" id="dob_year" class="form-control">
                                    <option value="">Year</option>
                                    @for($year = date('Y'); $year >= 1900; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="dob" id="dob">
                        <div class="invalid-feedback" id="dob_error"></div>
                        <small class="form-text text-muted" id="dob_display"></small>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block" id="submitBtn">Add Member</button>
                        <button type="button" class="btn btn-secondary btn-block" id="cancelEditBtn" style="display: none;">Cancel Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- List of Family Members -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title">Family Members</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="familyMembersTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Date of Birth</th>
                            <th>Age</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTables will populate this automatically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="manageEventsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Events for <span id="eventMemberName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add Event Form -->
                <form id="addEventForm">
                    @csrf
                    <input type="hidden" name="node_id" id="eventNodeId">
                    <div class="form-group">
                        <label for="event_name">Event Name</label>
                        <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Enter event name">
                        <div class="invalid-feedback" id="event_name_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="event_date">Event Date</label>
                        <input type="date" name="event_date" id="event_date" class="form-control">
                        <div class="invalid-feedback" id="event_date_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Optional"></textarea>
                    </div>
                    <button type="button" class="btn btn-primary" id="addEventBtn">Add Event</button>
                </form>
                <!-- Event List -->
                <h5 class="mt-4">Existing Events</h5>
                <table class="table table-bordered" id="eventsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be dynamically loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="manageRelationshipsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Relationships for <span id="relationMemberName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add Relationship Form -->
                <form id="addRelationshipForm">
                    @csrf
                    <input type="hidden" name="node1_id" id="relationNodeId">
                    <div class="form-group">
                        <label for="node2_id">Select Family Member</label>
                        <select name="node2_id" id="node2_id" class="form-control">
                            <option value="">Choose Member</option>
                            <!-- Options will be loaded dynamically -->
                        </select>
                        <div class="invalid-feedback" id="node2_id_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="relationship_type">Relationship Type</label>
                        <select name="relationship_type" id="relationship_type" class="form-control">
                            <option value="">Choose Relationship</option>
                            <option value="parent">Parent</option>
                            <option value="child">Child</option>
                            <option value="spouse">Spouse</option>
                            <option value="sibling">Sibling</option>
                        </select>
                        <div class="invalid-feedback" id="relationship_type_error"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Relationship</button>
                </form>

                <!-- Existing Relationships -->
                <h5 class="mt-4">Existing Relationships</h5>
                <table class="table table-bordered" id="relationshipsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Related Member</th>
                            <th>Relationship</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be dynamically loaded -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<!-- Required JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        // Initialize DataTable
        // Initialize DataTable
var dataTable = $('#familyMembersTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route("admin.family.members.data") }}',
        type: 'GET'
    },
    columns: [
        { 
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },
        { 
            data: 'full_name',
            name: 'first_name',
            orderable: true,
            searchable: true
        },
        { 
            data: 'gender_formatted',
            name: 'gender',
            orderable: true,
            searchable: true
        },
        { 
            data: 'dob_formatted',
            name: 'dob', // This tells DataTables to use the 'dob' database column for sorting
            orderable: true,
            searchable: true
        },
        { 
            data: 'age',
            name: 'dob', // Age also uses dob column for searching
            orderable: false,
            searchable: true
        },
        { 
            data: 'actions',
            name: 'actions',
            orderable: false,
            searchable: false
        }
    ],
    order: [[3, 'asc']], // Default sort by DOB column (index 3) ascending
    language: {
        emptyTable: "No family members found. Add your first family member!",
        processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
    },
    drawCallback: function() {
        // Re-initialize event handlers if needed
        $('[data-toggle="tooltip"]').tooltip();
    }
});

        // Enable Select2 on dropdowns
        $('#gender').select2({
            placeholder: "Select Gender",
            allowClear: true
        });
        
        // Enable Select2 on date dropdowns
        $('#dob_day, #dob_month, #dob_year').select2({
            placeholder: function() {
                return $(this).attr('name').replace('dob_', '');
            },
            allowClear: true
        });

        // Clear validation errors when input changes
        $('input, select').on('input change', function() {
            $(this).removeClass('is-invalid');
            const fieldId = this.id === 'dob' ? 'dob_day' : this.id;
            $(`#${fieldId}_error`).text('');
        });

        // Function to update the hidden dob field and display
        function updateDOB() {
            const day = $('#dob_day').val();
            const month = $('#dob_month').val();
            const year = $('#dob_year').val();
            
            if (day && month && year) {
                // Format as YYYY-MM-DD
                const dob = `${year}-${month}-${day}`;
                $('#dob').val(dob);
                
                // Format for display
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'];
                const displayDate = `${parseInt(day)} ${monthNames[parseInt(month) - 1]}, ${year}`;
                $('#dob_display').text(`Selected: ${displayDate}`);
            } else {
                $('#dob').val('');
                $('#dob_display').text('');
            }
        }

        // Update dob when date components change
        $('#dob_day, #dob_month, #dob_year').on('change', function() {
            updateDOB();
        });

        // Validate form function
        function validateForm() {
            let isValid = true;
            
            // Check First Name
            if (!$('#first_name').val().trim()) {
                $('#first_name').addClass('is-invalid');
                $('#first_name_error').text('First name is required.');
                isValid = false;
            }
            
            // Check Last Name
            if (!$('#last_name').val().trim()) {
                $('#last_name').addClass('is-invalid');
                $('#last_name_error').text('Last name is required.');
                isValid = false;
            }
            
            // Check Gender
            if (!$('#gender').val()) {
                $('#gender').addClass('is-invalid');
                $('#gender_error').text('Gender is required.');
                isValid = false;
            }
            
            // Check Date of Birth components
            const day = $('#dob_day').val();
            const month = $('#dob_month').val();
            const year = $('#dob_year').val();
            
            if (!day || !month || !year) {
                $('#dob_day, #dob_month, #dob_year').addClass('is-invalid');
                $('#dob_error').text('Please select day, month and year.');
                isValid = false;
            } else {
                // Validate the actual date
                const dob = new Date(year, month - 1, day);
                const today = new Date();
                
                // Check if date is valid (handles invalid dates like Feb 30)
                if (dob.getDate() != day || dob.getMonth() + 1 != month || dob.getFullYear() != year) {
                    $('#dob_day, #dob_month, #dob_year').addClass('is-invalid');
                    $('#dob_error').text('Invalid date selected.');
                    isValid = false;
                }
                // Check if date is in the future
                else if (dob > today) {
                    $('#dob_day, #dob_month, #dob_year').addClass('is-invalid');
                    $('#dob_error').text('Date of birth cannot be in the future.');
                    isValid = false;
                }
            }
            
            return isValid;
        }

        // Reset form to add mode
        function resetForm() {
            $('#familyMemberForm')[0].reset();
            $('#member_id').val('');
            $('#form_method').val('POST');
            $('#formTitle').text('Add Family Member');
            $('#submitBtn').text('Add Member').removeClass('btn-primary').addClass('btn-success');
            $('#cancelEditBtn').hide();
            $('#gender').val(null).trigger('change');
            $('#dob_day, #dob_month, #dob_year').val(null).trigger('change');
            $('#dob_display').text('');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }

        // Handle form submit for both add and edit
        $('#familyMemberForm').submit(function(e) {
            e.preventDefault();

            // Validate form
            if (!validateForm()) {
                return;
            }

            // Determine if we're adding or updating
            const method = $('#form_method').val();
            const memberId = $('#member_id').val();
            let url, successMessage;

            if (method === 'PUT') {
                // Properly construct the update URL with the id parameter
                url = '{{ route("admin.family.update", ["id" => ":id"]) }}'.replace(':id', memberId);
                successMessage = 'Family member updated successfully!';
            } else {
                url = '{{ route("admin.family.store") }}';
                successMessage = 'Family member added successfully!';
            }

            // Show loading state
            const submitBtn = $('#submitBtn');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

            // Prepare form data
            const formData = $(this).serialize();

            // AJAX request
            $.ajax({
                url: url,
                method: method === 'PUT' ? 'PUT' : 'POST',
                data: formData,
                success: function(response) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: successMessage,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Refresh DataTable to show updated data
                    dataTable.ajax.reload(null, false);

                    // Reset form
                    resetForm();
                    
                    // Reset button state
                    submitBtn.prop('disabled', false).html(originalText);
                },
                error: function(xhr) {
                    // Reset button state
                    submitBtn.prop('disabled', false).html(originalText);
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            const fieldId = key === 'dob' ? 'dob_day' : key;
                            $(`#${fieldId}`).addClass('is-invalid');
                            $(`#${fieldId}_error`).text(value[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error!',
                            text: 'Please check the form for errors.',
                        });
                    } else {
                        // Other errors
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'An error occurred while processing the request.',
                        });
                    }
                }
            });
        });

        // Cancel edit
        $('#cancelEditBtn').click(function() {
            resetForm();
        });

        // Edit family member - using event delegation for dynamically loaded buttons
        $(document).on('click', '.edit-btn', function() {
            const memberId = $(this).data('id');
            
            // Show loading
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching member details',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Properly construct the show URL with the id parameter
            const showUrl = '{{ route("admin.family.show", ["id" => ":id"]) }}'.replace(':id', memberId);
            
            // Fetch member details
            $.ajax({
                url: showUrl,
                method: 'GET',
                success: function(response) {
                    Swal.close();
                    
                    // Populate form with member data
                    $('#member_id').val(response.id);
                    $('#first_name').val(response.first_name);
                    $('#last_name').val(response.last_name);
                    $('#gender').val(response.gender).trigger('change');
                    
                    // Parse and set date components
                    const dob = new Date(response.dob);
                    const day = dob.getDate().toString().padStart(2, '0');
                    const month = (dob.getMonth() + 1).toString().padStart(2, '0');
                    const year = dob.getFullYear();
                    
                    $('#dob_day').val(day).trigger('change');
                    $('#dob_month').val(month).trigger('change');
                    $('#dob_year').val(year).trigger('change');
                    
                    // Update form for edit mode
                    $('#form_method').val('PUT');
                    $('#formTitle').text('Edit Family Member');
                    $('#submitBtn').text('Update Member').removeClass('btn-success').addClass('btn-primary');
                    $('#cancelEditBtn').show();
                    
                    // Scroll to form
                    $('html, body').animate({
                        scrollTop: $('#familyMemberForm').offset().top - 100
                    }, 500);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to fetch member details.'
                    });
                }
            });
        });

        // Delete family member - using event delegation
        $(document).on('click', '.delete-btn', function() {
            const memberId = $(this).data('id');
            const memberName = $(this).closest('tr').find('td:nth-child(2)').text();
            
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${memberName}. This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If we're editing this member, reset the form first
                    if ($('#member_id').val() == memberId) {
                        resetForm();
                    }
                    
                    // Properly construct the destroy URL with the id parameter
                    const deleteUrl = '{{ route("admin.family.destroy", ["id" => ":id"]) }}'.replace(':id', memberId);
                    
                    $.ajax({
                        url: deleteUrl,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Family member has been deleted.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            // Refresh DataTable
                            dataTable.ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to delete family member. Please try again.'
                            });
                        }
                    });
                }
            });
        });

        // Open the Manage Events Modal - using event delegation
        $(document).on('click', '.manage-events-btn', function() {
            const nodeId = $(this).data('id');
            const memberName = $(this).data('name');

            // Set modal title and hidden node ID
            $('#eventMemberName').text(memberName);
            $('#eventNodeId').val(nodeId);

            // Clear form inputs
            $('#addEventForm')[0].reset();

            // Fetch events for this family member
            $.ajax({
                url: `/admin/family/${nodeId}/events`,
                method: 'GET',
                success: function(events) {
                    let rows = '';
                    events.forEach((event, index) => {
                        rows += `
                            <tr id="event-row-${event.id}">
                                <td>${index + 1}</td>
                                <td>${event.event_name}</td>
                                <td>${event.event_date}</td>
                                <td>${event.description || ''}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-event-btn" data-id="${event.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#eventsTable tbody').html(rows);
                    $('#manageEventsModal').modal('show');
                }
            });
        });

        // Add Event
        $('#addEventBtn').on('click', function() {
            const formData = $('#addEventForm').serialize();
            const nodeId = $('#eventNodeId').val();

            $.ajax({
                url: '/admin/family/events',
                method: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire('Success!', 'Event added successfully.', 'success');

                    // Append event to the table
                    const newRow = `
                        <tr id="event-row-${response.id}">
                            <td>${$('#eventsTable tbody tr').length + 1}</td>
                            <td>${response.event_name}</td>
                            <td>${response.event_date}</td>
                            <td>${response.description || ''}</td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-event-btn" data-id="${response.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                    $('#eventsTable tbody').append(newRow);

                    // Reset the form
                    $('#addEventForm')[0].reset();
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Failed to add event.', 'error');
                }
            });
        });

        // Delete Event
        $(document).on('click', '.delete-event-btn', function() {
            const eventId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to delete this event.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/family/events/${eventId}`,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function() {
                            Swal.fire('Deleted!', 'The event has been deleted.', 'success');
                            $(`#event-row-${eventId}`).remove();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Failed to delete event.', 'error');
                        }
                    });
                }
            });
        });

        // Open Relationship Modal - using event delegation
        $(document).on('click', '.manage-relationships-btn', function() {
            const nodeId = $(this).data('id');
            const memberName = $(this).data('name');

            $('#relationMemberName').text(memberName);
            $('#relationNodeId').val(nodeId);
            $('#addRelationshipForm')[0].reset();
            
            // Load family members for the dropdown
            $.ajax({
                url: '/admin/family/members/data',
                method: 'GET',
                data: { draw: 1, start: 0, length: 100 }, // Get first 100 members
                success: function(response) {
                    let options = '<option value="">Choose Member</option>';
                    response.data.forEach(function(member) {
                        // Don't include the current member in the list
                        if (member.id != nodeId) {
                            options += `<option value="${member.id}">${member.first_name} ${member.last_name}</option>`;
                        }
                    });
                    $('#node2_id').html(options);
                    
                    // Now fetch existing relationships
                    $.ajax({
                        url: `/admin/family/${nodeId}/relationships`,
                        method: 'GET',
                        success: function(relationships) {
                            let rows = '';
                            relationships.forEach((relation, index) => {
                                rows += `
                                    <tr id="relation-row-${relation.id}">
                                        <td>${index + 1}</td>
                                        <td>${relation.related_member}</td>
                                        <td>${relation.relationship_type}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm delete-relationship-btn" data-id="${relation.id}">Delete</button>
                                        </td>
                                    </tr>
                                `;
                            });
                            $('#relationshipsTable tbody').html(rows);
                            $('#manageRelationshipsModal').modal('show');
                        }
                    });
                }
            });
        });

        // Add Relationship
        $('#addRelationshipForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '/admin/family/relationships',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire('Success!', 'Relationship added successfully.', 'success');

                    const newRow = `
                        <tr id="relation-row-${response.id}">
                            <td>${$('#relationshipsTable tbody tr').length + 1}</td>
                            <td>${response.related_member}</td>
                            <td>${response.relationship_type}</td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-relationship-btn" data-id="${response.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                    $('#relationshipsTable tbody').append(newRow);
                    $('#addRelationshipForm')[0].reset();
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Failed to add relationship.', 'error');
                }
            });
        });

        // Delete Relationship
        $(document).on('click', '.delete-relationship-btn', function() {
            const relationId = $(this).data('id');

            $.ajax({
                url: `/admin/family/relationships/${relationId}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    Swal.fire('Deleted!', 'Relationship has been removed.', 'success');
                    $(`#relation-row-${relationId}`).remove();
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to delete relationship.', 'error');
                }
            });
        });

    });
</script>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<style>
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .invalid-feedback {
        display: block;
    }
    .select2-container .select2-selection--single {
        height: calc(1.5em + .75rem + 2px);
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: calc(1.5em + .75rem);
    }
    #dob_display {
        margin-top: 5px;
        font-weight: bold;
        color: #28a745;
    }
    table.dataTable {
        width: 100% !important;
        margin: 0 auto;
        clear: both;
        border-collapse: separate;
        border-spacing: 0;
    }
</style>
@endsection