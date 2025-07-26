@extends('layouts.guest')

@php
    $title = 'Approved Reviews';
@endphp

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    
    <!-- Bootstrap CSS (optional for styling) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <style>
        body {
            padding: 20px;
        }

        table.dataTable {
            width: 100% !important;
        }

        .update-status-container {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h2 class="mb-4">Approved Reviews</h2>

        @auth
            <table id="reviewsTable" class="display table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Images</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be dynamically loaded via DataTables -->
                </tbody>
            </table>
        @endauth

        @guest
            <p>You must be logged in to view reviews.</p>
        @endguest
    </div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#reviewsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("reviews.approvedData") }}',
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
                    { data: 'review' },
                    { data: 'created_at', render: function(data) {
                        return new Date(data).toLocaleDateString();
                    }},
                    {
                        data: 'images', render: function(data) {
                            console.log('Raw image data:', data); 
                            
                            if (data) {
                                try {
                                    const decodeHtml = (html) => {
                                        let txt = document.createElement('textarea');
                                        txt.innerHTML = html;
                                        return txt.value;
                                    };
                                    
                                    let decodedData = decodeHtml(data);
                                    let images = JSON.parse(decodedData);
                                    console.log('Parsed images array:', images); 
                                    
                                    return images.map(function(image) {
                                        let imageUrl = image.replace('public/', '/storage/');
                                        return `<a href="${imageUrl}" target="_blank">IMG</a>`;
                                    }).join(', ');
                                } catch (e) {
                                    console.error('Image data parse error:', e);
                                    return 'Error parsing images';
                                }
                            }
                            
                            return 'No Images';
                        }
                    }
                ],
            });
        });
    </script>
@endpush
