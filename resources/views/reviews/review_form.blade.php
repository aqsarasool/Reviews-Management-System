@extends('layouts.guest')
@php
    $title = 'Review Form';
@endphp

@section('title', 'Form')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 70px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin-top: 170px !important;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        form div {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="email"], select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .dz-message {
            margin: 15px 0;
            text-align: center;
            color: #888;
            border: 2px dashed #ccc;
            padding: 30px 20px;
            border-radius: 6px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .dropzone {
            border: none;
            box-shadow: none; 
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .alert-success, .alert-error {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .rating {
            direction: rtl;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 2em;
            color: #ddd;
            cursor: pointer;
        }
        .rating label:hover,
        .rating label:hover ~ label,
        .rating input:checked ~ label {
            color: #f7d106;
        }
        .rating input:checked + label {
            color: #f7d106;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-5">
        <h2>Submit Your Review</h2>
        <div id="success-message" class="alert-success" style="display: none;"></div>
    
        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data" class="dropzone" id="review-dropzone">
            @csrf
            <div>
                <input type="text" name="first_name" id="first_name" placeholder="First Name" required>
            </div>
            <div>
                <input type="text" name="last_name" id="last_name" placeholder="Last Name" required>
            </div>
           
            <div>
                <input type="email" name="email" id="email" placeholder="Email" required>
            </div>
            <div class="rating">
                <input type="radio" id="star5" name="rating" value="5" required />
                <label for="star5" title="5 stars"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="star4" name="rating" value="4" required />
                <label for="star4" title="4 stars"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="star3" name="rating" value="3" required />
                <label for="star3" title="3 stars"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="star2" name="rating" value="2" required />
                <label for="star2" title="2 stars"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="star1" name="rating" value="1" required />
                <label for="star1" title="1 star"><i class="fa-solid fa-star"></i></label>
            </div>
            <div>
                <input type="text" name="title" id="title" placeholder="Title" required>
            </div>
            <div>
                <textarea name="review" id="review" placeholder="Write your review" required></textarea>
            </div>
            <div class="dz-message">Upload your Cloudster Pillow photos here</div>
            <div id="error-message" class="alert-error" style="display: none;"></div>

            <button type="submit" id="submit-button">Submit</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"></script>
    <script>
        Dropzone.options.reviewDropzone = {
            paramName: 'images',
            acceptedFiles: '.png,.pdf,.jpeg,.jpg',
            addRemoveLinks: true,
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            clickable: '.dz-message',
            init: function() {
                let myDropzone = this;
                let submitButton = document.getElementById("submit-button");
                let successMessageElement = document.getElementById('success-message');
                let errorMessageElement = document.getElementById('error-message');
                let form = document.getElementById('review-dropzone');

                function validateForm() {
                    const firstName = document.getElementById('first_name').value.trim();
                    const lastName = document.getElementById('last_name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const title = document.getElementById('title').value.trim();
                    const review = document.getElementById('review').value.trim();
                    const rating = document.querySelector('input[name="rating"]:checked');

                    if (firstName && lastName && email && title && review && rating) {
                        return true;
                    } else {
                        return false;
                    }
                }

                submitButton.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (validateForm()) {
                        errorMessageElement.style.display = 'none';
                        submitButton.disabled = true;

                        if (myDropzone.getQueuedFiles().length > 0) {
                            myDropzone.processQueue();
                        } else {
                            form.submit();
                        }
                    } else {
                        errorMessageElement.textContent = 'Please fill out all required fields';
                        errorMessageElement.style.display = 'block';
                    }
                });

                this.on("sending", function(file, xhr, formData) {
                    formData.append("_token", "{{ csrf_token() }}");
                });

                this.on("queuecomplete", function() {
                    if (myDropzone.getAcceptedFiles().length === 0) {
                        form.submit();
                    }
                });

                this.on("success", function(file, response) {
                    myDropzone.removeAllFiles();
                    form.reset();
                    if (successMessageElement) {
                        successMessageElement.innerHTML = 'Review submitted successfully!';
                        successMessageElement.style.display = 'block';
                    } else {
                        console.error('Success message element not found');
                    }
                    submitButton.disabled = false;  // Re-enable the button after success
                });

                this.on("error", function(file, response) {
                    console.error(response);
                    submitButton.disabled = false;  // Re-enable the button after error
                });
            }
        };
    </script>
@endpush
