@extends('layouts.guest')
@php
$title = 'Display';
@endphp
@section('content')
    <div class="reviews-section mt-5">
        <h2 class="reviews-title">Customer Reviews</h2>
        <div class="stars-1">★★★★★</div>
        <p class="review-count" id="review-count">0 Reviews</p>
        <div id="reviews-container"></div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('styles.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .carousel-container {
        position: relative;
        width: 100%;
        max-width: 300px;
        margin-top: 20px;
        margin-left: 0px;
        overflow: hidden;
        height: auto;
        
    }

    .carousel-images {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    .carousel-image {
        min-width: 100%;
        box-sizing: border-box;
    }

    .carousel-nav {
        position: absolute;
        top: 50%;
        width: 100%;
        display: flex;
        justify-content: space-between;
        transform: translateY(-50%);
    }

    .carousel-nav button {
        background: rgba(95, 95, 95, 0.5);
        border: none;
        color: white;
        padding: 10px;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
    <script>
        const reviewUrl = "{{ route('reviews.get') }}";

        fetch(reviewUrl)
            .then(response => response.json())
            .then(data => {
                const reviewCount = data.length;
                document.getElementById('review-count').textContent = `${reviewCount} Reviews`;

                const reviewsContainer = document.getElementById('reviews-container');
                reviewsContainer.innerHTML = '';

                data.forEach(review => {
                    const reviewCard = `
                        <div class="review-card">
                            <div class="row">
                                <div class="col-4 review-header">
                                    <div class="avatar">
                                        <span class="avatar-text" >${review.first_name[0].toUpperCase()}</span>
                                        <span class="blue-tick">&#10004;</span>
                                    </div>
                                    <div class="review-info">
                                        <h6 style="font-weight:bold; padding-top:15px;">${review.first_name} ${review.last_name}</h6>
                                        <p style="font-weight:200;">${review.location}</p>
                                        <p style="font-style:italic;">${new Date(review.created_at).toLocaleDateString()}</p>
                                    </div>
                                </div>
                                <div class="col-8 review-body">
                                    <div class="stars">${'★'.repeat(review.rating) + '☆'.repeat(5 - review.rating)}</div>
                                    <h4 style="font-weight:bold;">${review.title}</h4>
                                    <p>Product: ${review.product_name}</p>
                                    <p>${review.review}</p>
                                    ${review.images ? `
                                        <div class="carousel-container">
                                            <div class="carousel-images">
                                                ${JSON.parse(review.images).map(image => {
                                                    const imageUrl = image.replace('public/', '/storage/');
                                                    return `<img src="${imageUrl}" alt="Review Image" class="carousel-image">`;
                                                }).join('')}
                                            </div>
                                            <div class="carousel-nav">
                                                <button id="prev-btn"><i class="fas fa-chevron-left"></i></button>
                                                <button id="next-btn"><i class="fas fa-chevron-right"></i></button>
                                            </div>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                    reviewsContainer.innerHTML += reviewCard;
                });

                // JavaScript for carousel functionality
                document.querySelectorAll('.carousel-container').forEach(container => {
                    const images = container.querySelector('.carousel-images');
                    const prevBtn = container.querySelector('#prev-btn');
                    const nextBtn = container.querySelector('#next-btn');
                    let currentIndex = 0;

                    function updateCarousel() {
                        const offset = -currentIndex * 100;
                        images.style.transform = `translateX(${offset}%)`;
                    }

                    prevBtn.addEventListener('click', () => {
                        currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.children.length - 1;
                        updateCarousel();
                    });

                    nextBtn.addEventListener('click', () => {
                        currentIndex = (currentIndex < images.children.length - 1) ? currentIndex + 1 : 0;
                        updateCarousel();
                    });

                    updateCarousel(); 
                });
            })
            .catch(error => console.error('Error fetching reviews:', error));
    </script>
@endpush
