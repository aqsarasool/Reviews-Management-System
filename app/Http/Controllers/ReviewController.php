<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return view('admin.reviews'); 
        }

        return redirect()->route('login'); 
    }
    public function showForm()
    {
            return view('reviews.review_form'); 
    }
    public function displayReviews()
    {
            return view('reviews.display'); 
        }
    public function showApprovedReviews()
    {
      return view('reviews.approved-views'); 
    }

    public function getReviewsData()
    {
        if (Auth::check()) {
            $reviews = Review::select(['id', 'first_name', 'last_name', 'email', 'rating','title', 'review', 'created_at', 'images', 'status','product_name', 'location','review_date'])
                ->get(); 

            return response()->json([
                'draw' => request()->get('draw'),
                'recordsTotal' => $reviews->count(),
                'recordsFiltered' => $reviews->count(),
                'data' => $reviews
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401); // Return unauthorized if not authenticated
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|mimes:png,pdf,jpeg,jpg',
            'review_date' => 'nullable|date',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $originalName = $image->getClientOriginalName();
                $path = $image->storeAs('public/images', $originalName);
                $imagePaths[] = $path;
            }
        }

        Review::create([
            'title' => $validated['title'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'rating' => $validated['rating'],
            'review' => $validated['review'],
            'images' => json_encode($imagePaths),
            'review_date' => now(), 
        ]);

        return redirect()->route('reviews.form')->with('success', 'Review submitted successfully!');
    }

  
    public function updateStatus(Request $request)
    {
        $data = $request->input('reviews');
    
        foreach ($data as $item) {
            $review = Review::find($item['id']);
            if ($review) {
                $review->status = $item['status'];
                $review->save();
            }
        }
    
        return response()->json(['success' => true]);
    }
      

    public function approvedReviewsData()
    {
        return datatables()->eloquent(Review::where('status', 'approved'))
            ->editColumn('images', function($review) {
                $images = $review->images; 
                if (is_array($images)) {
                    return json_encode($images); 
                }
                return $images; 
            })
            ->toJson();
    }

public function getReviews()
{
    $reviews = Review::where('status', 'approved')->get();
    return response()->json($reviews);
}

public function update(Request $request, $id)
{
    
       
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|mimes:png,pdf,jpeg,jpg',
            'product_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'review_date' => 'required|date',
        ]);

     
        $review = Review::find($id);

        if (!$review) {
            return redirect()->route('reviews.index')->with('error', 'Review not found!');
        }


        $imagePaths = $review->images ? json_decode($review->images, true) : [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $originalName = $image->getClientOriginalName();
                $path = $image->storeAs('public/images', $originalName);
                $imagePaths[] = $path;
            }
        }


     
        $review->update([
            'title' => $validated['title'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'rating' => $validated['rating'],
            'review' => $validated['review'],
            'product_name' => $validated['product_name'],
            'location' => $validated['location'],
            'images' => json_encode($imagePaths),
            'review_date' => $validated['review_date'],
        ]);

        return redirect()->route('reviews.index')->with('success', 'Review updated successfully!');

    
}


public function edit($id)
{
    $review = Review::find($id);
    if (is_string($review->images)) {
        $review->images = json_decode($review->images, true);
    }
    return view('admin.edit_review', compact('review'));
    // Ensure $review->images is an array
  

 
}
// public function removeImage(Request $request)
// {
//     $imagePath = $request->input('imagePath');
//     if(file_exists('$imagePath'))
//     {
//         @unlink($imagePath);
//     }

//     return response()->json(['success' => true]);
// }


}