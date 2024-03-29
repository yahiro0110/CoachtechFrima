<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemDetailRequest;
use App\Http\Requests\UpdateItemImagesRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Status;
use App\Models\User;
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
    public function home()
    {
        return Inertia::render(
            'Home',
            [
                'items' => Item::select('id', 'name', 'brand', 'seller_id', 'category_id', 'price')
                    ->with(['itemImages', 'user.userImage', 'comments'])
                    ->WithFavoriteUserCount()
                    ->withUserAttached(Auth::id())
                    ->get(),
                'categories' => Category::select('id', 'name', 'parent_id')->get(),
                'conditions' => Condition::select('id', 'name')->get(),
            ]
        );
    }

    /**
     * 指定された商品を現在認証されているユーザーのお気に入りに登録する。
     *
     * 登録処理中に例外が発生した場合は、ホームページへリダイレクトし、
     * エラーメッセージを含む警告通知を表示する。
     *
     * @param  \App\Models\Item  $item お気に入りに登録する商品のインスタンス
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector 登録に失敗した場合のリダイレクトレスポンス
     */
    public function attachFavorite(Item $item)
    {
        try {
            $item->users()->attach(Auth::id());
            Redirect::route('home');
        } catch (\Exception $e) {
            return to_route('home')->with([
                'message' => 'お気に入り登録に失敗しました。',
                'status' => 'warning',
            ]);
        }
    }

    /**
     * 指定された商品を現在認証されているユーザーのお気に入りリストから削除する。
     *
     * 削除処理中に例外が発生した場合は、ホームページへリダイレクトし、
     * エラーメッセージを含む警告通知を表示する。
     *
     * @param  \App\Models\Item  $item お気に入りに登録する商品のインスタンス
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector 削除に失敗した場合のリダイレクトレスポンス
     */
    public function detachFavorite(Item $item)
    {
        try {
            $item->users()->detach(Auth::id());
            Redirect::route('home');
        } catch (\Exception $e) {
            return to_route('home')->with([
                'message' => 'お気に入りからの削除に失敗しました。',
                'status' => 'warning',
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function seller(Request $request)
    {
        return Inertia::render(
            'Seller',
            [
                'user' => User::with('userImage', 'userDetail')->findOrFail($request->id),
                'items' => Item::select('id', 'name', 'brand', 'seller_id', 'category_id', 'price')
                    ->where('seller_id', $request->id)
                    ->with(['itemImages', 'user.userImage', 'comments'])
                    ->WithFavoriteUserCount()
                    ->withUserAttached(Auth::id())
                    ->get(),
                'categories' => Category::select('id', 'name', 'parent_id')->get(),
                'conditions' => Condition::select('id', 'name')->get(),
            ]
        );
    }

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
                'purchases' => $item->purchases()->with('user', 'status', 'payment')->get(),
                'comments' => $item->comments()->with('user.userImage')->get(),
                'statuses' => Status::select('id', 'name')->get(),
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
     * 商品の詳細情報を更新する。
     *
     * 指定された商品の名前、カテゴリID、ブランド、状態ID、説明、および価格を更新する。
     * 更新が完了した後、商品の編集ページへリダイレクトする。
     *
     * @param \App\Http\Requests\UpdateItemDetailRequest $request HTTPリクエストインスタンス、更新する商品情報（名前、カテゴリID、ブランド、状態ID、説明、価格）を含む
     * @param \App\Models\Item $item 更新する対象の商品モデルインスタンス
     * @return \Illuminate\Http\RedirectResponse 商品の編集ページへのリダイレクトレスポンス
     */
    public function updateDetail(UpdateItemDetailRequest $request, Item $item)
    {
        $item->update($request->only(['name', 'category_id', 'brand', 'condition_id', 'description', 'price']));

        return Redirect::route('items.edit', ['item' => $item->id]);
    }

    /**
     * 既存の商品画像を更新し、新しい画像をアップロードする。
     *
     * このメソッドではまず、リクエストされた画像IDに基づいて既存の商品画像を削除する。
     * 次に、新しくアップロードされたファイルを保存し、それらの情報をデータベースに記録する。
     * 更新が完了した後、商品の編集ページへリダイレクトする。
     *
     * @param \App\Http\Requests\UpdateItemImagesRequest $request HTTPリクエストインスタンス、アップロードされたファイルや画像IDの配列を含む
     * @param \App\Models\Item $item 画像を更新する対象の商品モデルインスタンス
     * @return \Illuminate\Http\RedirectResponse 商品の編集ページへのリダイレクトレスポンス
     */
    public function updateImages(UpdateItemImagesRequest $request, Item $item)
    {
        // 既存の画像を削除
        // $request->itemImagesから、提出された画像IDの配列を取得する。
        // array_column関数を使用して、$request->itemImagesから'id'キーの値のみを取得する。
        $itemImages = is_array($request->itemImages) ? $request->itemImages : [];
        $submittedImageIds = array_column($itemImages, 'id');

        foreach ($item->itemImages as $image) {
            // $imageのIDが提出されたID配列に含まれていない場合、その画像を削除
            if (!in_array($image->id, $submittedImageIds)) {
                // storageから画像ファイルを削除
                Storage::delete('public/images/items/' . $image->image_path);

                // データベースから画像レコードを削除
                $image->delete();
            }
        }

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
