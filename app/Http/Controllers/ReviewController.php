<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Tourism;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use ApiResponser;
    public function add(Request $request)
    {
        try {
            $this->validate($request, [
                'tourism_id' => 'required|exists:tourisms,id',
                'rating' => 'required|in:1,2,3,4,5',
                'review' => 'required',
                'image' => 'required|image'
            ]);

            // Init Review
            $review = $request->toArray();

            $review['user_id'] = Auth::id();

            // Upload image
            $review['image'] = $request->file('image')->store('uploads');

            $review = Review::create($review);

            return $this->successResponse(new ReviewResource($review));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function list(Request $request)
    {
        $this->validate($request, [
            'tourism_id' => 'required|exists:tourisms,id'
        ]);

        $reviews = Review::where('tourism_id', $request->tourism_id)->get();

        return $this->successResponse(ReviewResource::collection($reviews));
    }

    public function update()
    {
        //
    }

    public function delete($id)
    {
        try {
            $review = Review::where('id', $id)->first();

            if ($review)
                $review->delete();
            else
                return $this->errorResponse('review not found', 404);

            return $this->successResponse(true);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
