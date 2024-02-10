<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();
        return Inertia::render(
            'Items/Index',
            [
                'items' => Item::select('id', 'name', 'brand', 'price')
                    ->where('seller_id', $userId)
                    ->with('itemImages')
                    ->get(),
                'categories' => Category::select('id', 'name', 'parent_id')->get(),
                'conditions' => Condition::select('id', 'name')->get(),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render(
            'Items/Create',
            [
                'categories' => Category::select('id', 'name', 'parent_id')->get(),
                'conditions' => Condition::select('id', 'name')->get(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemRequest $request)
    {
        // 商品情報をデータベースに保存
        $item = Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'condition_id' => $request->condition_id,
            'price' => $request->price,
            'brand' => $request->brand,
            'seller_id' => Auth::id(),
            'category_id' => $request->category_id,
        ]);

        // 複数ファイルのアップロードを処理
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // 新しいファイル名を生成し、ファイルを保存
                $file_name = date('Ymd') . Str::random(15) . '_' . $file->getClientOriginalName();
                $file->storeAs('public/images/items', $file_name);

                // データベースに保存
                ItemImage::create([
                    'item_id' => $item->id,
                    'image_path' => $file_name,
                ]);
            }
        }

        return Redirect::route('items.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return Inertia::render(
            'Items/Show',
            [
                'item' => Item::with('itemImages')->findOrFail($item->id),
                'categories' => Category::select('id', 'name', 'parent_id')->get(),
                'conditions' => Condition::select('id', 'name')->get(),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return Inertia::render(
            'Items/Edit',
            [
                'item' => Item::with('itemImages')->findOrFail($item->id),
                'categories' => Category::select('id', 'name', 'parent_id')->get(),
                'conditions' => Condition::select('id', 'name')->get(),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateItemRequest  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function updateDetail(Request $request, Item $item)
    {
        $item->update($request->only(['name', 'category_id', 'brand', 'condition_id', 'description', 'price']));

        return Redirect::route('items.edit', ['item' => $item->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        try {
            $item->delete();

            return to_route('items.index')->with([
                'message' => '商品を削除しました。',
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return to_route('items.index')->with([
                'message' => '商品の削除に失敗しました。',
                'status' => 'warning',
            ]);
        }
    }
}
