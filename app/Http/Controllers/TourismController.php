<?php

namespace App\Http\Controllers;

use App\Http\Resources\TourismResource;
use App\Models\Category;
use App\Models\Tourism;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use App\Helpers\MLHelper;

class TourismController extends Controller
{
    use ApiResponser;
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'category' => 'required|exists:categories,name',
                'city' => 'required',
                'price' => 'required|integer',
                'rating' => 'integer|in:1,2,3,4,5',
                'time_minutes' => '',
                'coordinate' => '',
                'thumbnail' => 'required|image'
            ]);

            $tourism = $request->toArray();

            // set user id
            $tourism['user_id'] = Auth::id();

            // Upload image
            $tourism['thumbnail'] = $request->file('thumbnail')->store('uploads');

            // Set category Id
            $category = Category::where('name', $request->category)->first();
            $tourism['category_id'] = $category->id;
            unset($tourism['category']);

            // save tourism
            $store = Tourism::create($tourism);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
        DB::commit();
        return $this->successResponse($store);
    }

    public function show($id)
    {
        try {
            $tourism = Tourism::with(['user', 'reviews', 'category'])->where('id', $id)->first();

            if ($tourism) {
                return $this->successResponse(new TourismResource($tourism));
            } else
                return $this->errorResponse('Tourism not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function list(Request $request)
    {
        // define init state
        $itemsPerPage = 30;

        try {
            // validate request
            $this->validate($request, [
                'page' => '',
                'search' => '',
                'order' => '',
                'orderby' => '',
                'category' => ''
            ]);

            // begin query
            $tourisms = Tourism::query()->with(['category', 'user']);

            // perform search
            if (!empty($request->search)) {
                $search = $request->search;
                $tourisms = $tourisms->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhereHas('categories', function (Builder $query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            }

            // perform category
            if (!empty($request->category)) {
                $category = $request->category;

                if (!is_string($category))
                    throw new \Exception('Category hanya boleh berupa huruf.');

                $tourisms = $tourisms->whereHas('category', function (Builder $query) use ($category) {
                    $query->where('name', 'like', "%{$category}%");
                });
            }

            // perform orderby
            if (!empty($request->order) && !empty($request->orderby)) {
                $tourisms = $tourisms->orderBy($request->orderby, $request->order);
            }

            // perform paginate
            if (!empty($request->page)) {
                $offset = ($request->page - 1) * $itemsPerPage;
                $tourisms = $tourisms->offset($offset);
            }

            $tourisms = $tourisms->limit($itemsPerPage)->get();

            return $this->successResponse(TourismResource::collection($tourisms));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function favorited()
    {
        try {
            $userId = Auth::id();
            $tourisms = Tourism::whereHas('favorites', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->get();

            return $this->successResponse(TourismResource::collection($tourisms));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        //
    }

    public function delete($id)
    {
        try {
            $tourism = Tourism::find($id);

            if ($tourism) {
                $tourism->delete();
                return $this->successResponse(true);
            } else
                return $this->errorResponse('Tourism not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function recomendation()
    {
        try {
            $visited = DB::table('reviews')->where('user_id', Auth::id())->pluck('tourism_id')->toArray();
            $unvisited = DB::table('tourisms')->whereNotIn('id', $visited)->pluck('id')->toArray();

            $convertToMlId = array_values(array_unique(MLHelper::convertLocalToML($unvisited)));

            $arrayUserId = TourismController::loopUserId(Auth::id(), count($unvisited));

            $response = Http::post(config('app.flask_host').'/prediction', [
                'user_id' => $arrayUserId,
                'tourism_id' => $convertToMlId
            ]);
            $json = $response->json();
            $recomendedIds = array_slice($json, 0, 20);
            
            $convertToLocalId = array_values(array_unique(MLHelper::convertMLToLocal($recomendedIds)));

            $ids_ordered = implode(',', $convertToLocalId);

            $items = Tourism::whereIn('id', $convertToLocalId)->orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->get();

            return $this->successResponse(TourismResource::collection($items));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    private static function loopUserId($userId, $total)
    {
        $arr = [];
        for ($i = 0; $i < $total; $i++) {
            $arr[] = $userId;
        }
        return $arr;
    }
}
