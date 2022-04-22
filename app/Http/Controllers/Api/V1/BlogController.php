<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Utils\ErrorType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    const TITLE = 'title';
    const SUBTITLE = 'subtitle';
    const DESCRIPTION = 'description';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blog =  Blog::all();
        return jsend_success(BlogResource::collection($blog));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogRequest $request)
    {
        // DB::beginTransaction();
        // try {
        //     $code = trim($request->input(self::CODE));
        //     $name = trim($request->input(self::NAME));
        //     $image = $request->file(self::IMAGE);
        //     $buy_price = trim($request->input(self::BUY_PRICE));
        //     $sale_price = trim($request->input(self::SALE_PRICE));
        //     $image_name = FileUploadService::save($image, "items");
        //     $item = new $this->model;
        //     $item->uuid = Str::uuid()->toString();
        //     $item->code = $code;
        //     $item->name = $name;
        //     $item->image = $image_name;
        //     $item->buy_price = $buy_price;
        //     $item->sale_price = $sale_price;
        //     $item->save();
        //     DB::commit();
        //     return jsend_success(new ItemResource($item), JsonResponse::HTTP_CREATED);
        // } catch (Exception $ex) {
        //     DB::rollBack();
        //     Log::error(__('api.saved-failed', ['item' => class_basename($this->model)]), ['code' => $ex->getCode(),                'trace' => $ex->getTrace(),]);
        //     return jsend_error(__('api.saved-failed', ['item' => class_basename($this->model)]),                $ex->getCode(),                ErrorType::SAVE_ERROR);
        // }
        try {
            $title =  trim($request->get(self::TITLE));
            $subtitle =  trim($request->get(self::SUBTITLE));
            $description = trim($request->get(self::DESCRIPTION));

            $blog = new Blog();
            $blog->title = $title;
            $blog->subtitle = $subtitle;
            $blog->description = $description;

            $blog->save();
            return jsend_success(new BlogResource($blog),  JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error(__('api.saved-failed', ['model' => class_basename(Blog::class)]), [
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
            ]);

            return jsend_error(__('api.saved-failed', ['model' => class_basename(Blog::class)]), [
                $e->getCode(),
                ErrorType::SAVE_ERROR,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
