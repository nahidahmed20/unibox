<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        if (!auth('customer')->check()) {
            return back()->with('error', 'You must be logged in to review this product.');
        }

        $user = auth('customer')->user();

        $alreadyReviewed = Review::where('product_id', $id)
                            ->where('user_id', $user->id) 
                            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'এই পণ্যের জন্য আপনি আগে থেকেই একটি রিভিউ করেছেন।');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'product_id' => $id,
            'user_id'    => $user->id, 
            'user_name'  => $request->name, 
            'email'      => $request->email,
            'comment'    => $request->comment,
            'rating'     => $request->rating,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
