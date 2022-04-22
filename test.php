public function store(Request $request)    {        
    $request->validate([        'code' => 'required',            'name' => 'required',            'buy_price' => 'required',            'sale_price' => 'required',            'image' => 'required',        ]);        DB::beginTransaction();        try {            $code = trim($request->input(self::CODE));            $name = trim($request->input(self::NAME));            $image = $request->file(self::IMAGE);            $buy_price = trim($request->input(self::BUY_PRICE));            $sale_price = trim($request->input(self::SALE_PRICE));
            $image_name = FileUploadService::save($image, "items");
            $item = new $this->model;            $item->uuid = Str::uuid()->toString();            $item->code = $code;            $item->name = $name;            $item->image = $image_name;            $item->buy_price = $buy_price;            $item->sale_price = $sale_price;            $item->save();
            DB::commit();            return jsend_success(new ItemResource($item), JsonResponse::HTTP_CREATED);        } catch (Exception $ex) {            DB::rollBack();            Log::error(__('api.saved-failed', ['item' => class_basename($this->model)]), [                'code' => $ex->getCode(),                'trace' => $ex->getTrace(),            ]);
            return jsend_error(                __('api.saved-failed', ['item' => class_basename($this->model)]),                $ex->getCode(),                ErrorType::SAVE_ERROR            );        }    }
1:16
<?php
namespace App\Services;
use Illuminate\Support\Facades\Storage;
class FileUploadService{    public static function save($file, $location)    {        $now = now();        $filename = $now->toDateString() . '_' . $now->timestamp . '_' .$file->getClientOriginalName();        $file->storeAs("public/" . $location, $filename);        return $filename;    }
    public static function remove($filename, $location)    {        if ($filename) {            Storage::disk('public')->delete($location . '/' . $filename);        }    }}