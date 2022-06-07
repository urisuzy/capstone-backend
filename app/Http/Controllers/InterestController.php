<?php

namespace App\Http\Controllers;

use App\Http\Resources\InterestResource;
use App\Models\Category;
use App\Models\Interest;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterestController extends Controller
{
    use ApiResponser;
    public function add(Request $request)
    {
        try {
            $this->validate($request, [
                'interest1' => 'required|exists:categories,name',
                'interest2' => 'required|exists:categories,name',
                'interest3' => 'required|exists:categories,name'
            ]);

            $userId = Auth::id();
            $category1 = Category::where('name', $request->interest1)->first();
            $category2 = Category::where('name', $request->interest2)->first();
            $category3 = Category::where('name', $request->interest3)->first();

            Interest::upsert([
                ['user_id' => $userId, 'category_id' => $category1->id],
                ['user_id' => $userId, 'category_id' => $category2->id],
                ['user_id' => $userId, 'category_id' => $category3->id],
            ], ['user_id', 'category_id'], []);

            return $this->successResponse(true);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function list()
    {
        try {
            $userId = Auth::id();
            $interests = Interest::where('user_id', $userId)->get();

            return $this->successResponse(InterestResource::collection($interests));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'interest1' => 'required|exists:categories,name',
                'interest2' => 'required|exists:categories,name',
                'interest3' => 'required|exists:categories,name'
            ]);

            $userId = Auth::id();
            $category1 = Category::where('name', $request->interest1)->first();
            $category2 = Category::where('name', $request->interest2)->first();
            $category3 = Category::where('name', $request->interest3)->first();

            // Delete All Interests
            Interest::where('user_id', $userId)->delete();

            // Insert Again
            Interest::upsert([
                ['user_id' => $userId, 'category_id' => $category1->id],
                ['user_id' => $userId, 'category_id' => $category2->id],
                ['user_id' => $userId, 'category_id' => $category3->id],
            ], ['user_id', 'category_id'], []);

            return $this->successResponse(true);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function delete()
    {
        //
    }
}
