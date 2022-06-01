<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'brand_id' => 'max:50',
            'brand_name' => 'required|max:50',
            'serial_number' => 'max:50',
            'model' => 'max:50',
            'availability' => 'max:50',
            'date_received' => 'max:50',
            'date_sold' => 'max:50',
            'unit_type' => 'required|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error.'
            ], 422);
        }
        $product = Product::create($input);

        $this->historyOfAdd(Auth::id(),$product->id);
        return response()->json([
            'status' => true,
            'data' => $product,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product retrieve successfully',
            'data' => $product,
        ], 200);
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
        $updated = $request->all();
        $product = product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }

        $product->brand_id = $updated['brand_id'];
        $product->brand_name = $updated['brand_name'];
        $product->serial_number = $updated['serial_number'];
        $product->model = $updated['model'];
        $product->availability = $updated['availability'];
        $product->date_received = $updated['date_received'];
        $product->date_sold = $updated['date_sold'];
        $product->unit_type = $updated['unit_type'];

        if ($product->save()) {
            return response()->json([
                'status' => true,
                'data' => $product,

            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Product can not be updated'
            ], 500);
        }
    }

    public function historyOfRemove($edited_by, $product_id)
    {
        $history = new History();
        $history->edited_by = $edited_by;
        $history->product_id = $product_id;
        $history->date_of_remove = Carbon::now()->toDateTimeString();
        $history->save();
    }

    public function historyOfAdd($edited_by, $product_id)
    {
        $history = new History();
        $history->edited_by = $edited_by;
        $history->product_id = $product_id;
        $history->date_of_add = Carbon::now();
        $history->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);


        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }

        $this->historyOfRemove(Auth::id(), $product->id);
        if ($product->delete()) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post can not be deleted'
            ], 500);
        }
    }
}
