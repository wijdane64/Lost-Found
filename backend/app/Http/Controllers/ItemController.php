<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Http\JsonResponse;   
use Illuminate\Http\Response;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        $query = Item::with('user:id,name');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('location')) {
            $query->where('location', $request->location);
        }

        return response()->json(
            $query->latest()->get()
        );
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
       $item = Item::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'location' => $request->location,
            'date' => $request->date,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'تم إنشاء الغرض بنجاح',
            'item' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, $id)
    {
        $item = Item::findOrFail($id);

        // Authorization: only admin or the owner can update
        if (
            $request->user()->role !== 'admin' &&
            $item->user_id !== $request->user()->id
        ) {
            return response()->json([
                'message' => 'غير مسموح لك بتعديل هذا الغرض'
            ], 403);
        }

        $item->update($request->validated());

        return response()->json([
            'message' => 'تم التعديل بنجاح',
            'item' => $item
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    
   public function destroy(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        // Authorization: only admin or the owner can delete
        if (
            $request->user()->role !== 'admin' &&
            $item->user_id !== $request->user()->id
        ) {
            return response()->json([
                'message' => 'غير مسموح لك بحذف هذا الغرض'
            ], 403);
        }

        $item->delete();

        return response()->json([
            'message' => 'تم حذف الغرض بنجاح'
        ]);
    }
    // Get items of the authenticated user
    public function myItems(Request $request)
    {
        
        return response()->json(
            $request->user()
                ->items()
                ->latest()
                ->get()
        );
    }

}
