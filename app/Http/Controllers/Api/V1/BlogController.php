<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Services\FileUploadService;
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
    const IMAGE = 'image';
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

        try {
            $title =  trim($request->get(self::TITLE));
            $subtitle =  trim($request->get(self::SUBTITLE));
            $description = trim($request->get(self::DESCRIPTION));
            $image = $request->file(self::IMAGE);

            $image_name = FileUploadService::save($image, "blogs");

            $blog = new Blog();
            $blog->title = $title;
            $blog->subtitle = $subtitle;
            $blog->description = $description;
            $blog->image = $image_name;

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
    public function show(Blog $blog)
    {
        return jsend_success(new BlogResource($blog));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogRequest $request, Blog $blog)
    {
        try {

            $title =  trim($request->get(self::TITLE));
            $subtitle =  trim($request->get(self::SUBTITLE));
            $description = trim($request->get(self::DESCRIPTION));
            $image = $request->file(self::IMAGE);

            FileUploadService::remove($blog->image, "blogs");
            $image_name = FileUploadService::save($image, "blogs");

            $blog->title = $title;
            $blog->subtitle = $subtitle;
            $blog->description = $description;
            $blog->image = $image_name;

            $blog->save();
            return jsend_success(new BlogResource($blog),  JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error(__('api.saved-failed', ['model' => class_basename(Blog::class)]), [
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
            ]);

            return jsend_error(__('api.saved-failed', ['model' => class_basename(Blog::class)]), [
                $e->getCode(),
                ErrorType::UPDATE_ERROR,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BLog $blog)
    {
        try {

            $blog->delete();
            return jsend_success(null, JsonResponse::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            Log::error(__('api.saved-failed', ['model' => class_basename(Blog::class)]), [
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
            ]);

            return jsend_error(__('api.saved-failed', ['model' => class_basename(Blog::class)]), [
                $e->getCode(),
                ErrorType::DELETE_ERROR,
            ]);
        }
    }
}
