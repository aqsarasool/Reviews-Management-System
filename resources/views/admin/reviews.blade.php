@extends('layouts.guest')

@php
    $title = 'Reviews';
@endphp
@section('content')

<div class="container" style="margin-top: 20px;">
    <h2 class="mb-4">All Reviews</h2>
    @auth
    <table id="reviewsTable" class="display table table-bordered table-striped">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Rating</th>
                <th>Title</th>
                <th>Review</th>
                <th>Date</th>
                <th>Images</th>
                <th>Product</th> <!-- New column for Product Name -->
                <th>Location</th> <!-- New column for Location -->
                <th>Status</th>
                <th>Actions</th> <!-- New column for Edit button -->
            </tr>
        </thead>
        <tbody>
           
        </tbody>
    </table>

    <div class="update-status-container">
        <button id="updateStatusBtn" class="btn btn-primary">Update Status</button>
    </div>
    @endauth
</div>

@guest
    <p>You must be logged in to view reviews.</p>
@endguest
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<style>
    body {
       overflow-x: hidden;
margin-left: -217px !important;
height: auto;
margin-bottom: 50px;
margin-top: 60px;

    }

    table.dataTable {
        width: 100% !important;
    }

    .update-status-container {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
    }

    .btn-edit {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    text-decoration: none;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.btn-edit:hover {
    background-color: #0056b3; /* Darker shade for hover effect */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.btn-edit:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(38, 143, 255, 0.5); /* Blue outline on focus */
}

</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

<script>
$(document).ready(function() {
    var table = $('#reviewsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("reviews.data") }}',
            dataSrc: 'data',
            error: function (xhr, error, thrown) {
                console.error('AJAX Error:', error);
                console.error('Response:', xhr.responseText);
            }
        },
        columns: [
            { data: 'first_name' },
            { data: 'last_name' },
            { data: 'email' },
            { data: 'rating' },
            { data: 'title' },
            { data: 'review' },
            { data: 'review_date', render: function(data) {
                return new Date(data).toLocaleDateString();
            }},
            { data: 'images', render: function(data) {
                if (data) {
                    let images = JSON.parse(data);
                    return images.map(function(image) {
                        return '<a href="' + image.replace('public/', '/storage/') + '" target="_blank">IMG</a>';
                    }).join(', ');
                }
                return 'No Images';
            }},
            { data: 'product_name' },  // New column for Product Name
            { data: 'location' },      // New column for Location
            { data: 'status', render: function(data, type, row, meta) {
                return `
                    <select class="status-select" data-id="${row.id}">
                        <option value="pending" ${row.status === 'pending' ? 'selected' : ''}>Pending</option>
                        <option value="approved" ${row.status === 'approved' ? 'selected' : ''}>Approved</option>
                        <option value="rejected" ${row.status === 'rejected' ? 'selected' : ''}>Rejected</option>
                    </select>
                `;
            }},
            { data: 'id', render: function(data) {
                return `<button class="btn-edit btn btn-primary" data-id="${data}">Edit</button>`;
            }}
        ],
    });

    $('#updateStatusBtn').click(function() {
        var updatedData = [];
        
        table.rows().every(function(rowIdx) {
            var row = this.node();
            var statusSelect = $(row).find('.status-select');
            var status = statusSelect.val();
            var id = statusSelect.data('id');
            
            updatedData.push({
                id: id,
                status: status
            });
        });

        if (updatedData.length > 0) {
            $.ajax({
                url: '{{ route("reviews.updateStatus") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: { reviews: updatedData },
                success: function(response) {
                    if (response.success) {
                      
                        window.location.href = '{{ route("reviews.index") }}';
                    } else {
                        alert('Failed to update statuses.');
                    }
                },
                error: function(xhr, error, thrown) {
                    console.error('AJAX Error:', error);
                    console.error('Response:', xhr.responseText);
                }
            });
        } else {
            alert('No status changes to update.');
        }
    });

    $('#reviewsTable').on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        // Construct the URL by replacing ':id' with the actual review ID
        var editUrl = `{{ route('reviews.edit', ':id') }}`.replace(':id', id);
        // Redirect to the edit review page
        window.location.href = editUrl;
    });

});
</script>
@endpush
