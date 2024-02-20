<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return Inertia::render(
            'Purchases/Index',
            [
                'purchases' => $user->purchases()
                    ->select('id', 'item_id', 'status_id', 'ship_address', 'payment_id', 'created_at')
                    ->with('item.itemImages', 'status', 'payment')
                    ->get(),
                'statuses' => Status::select('id', 'name')->get(),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Item $item)
    {
        return Inertia::render(
            'Purchases/Create',
            [
                'item' => Item::with('itemImages', 'user.userImage')->findOrFail($item->id),
                'comments' => $item->comments()->with('user.userImage')->get(),
                'categories' => Category::select('id', 'name', 'parent_id')->get(),
                'conditions' => Condition::select('id', 'name')->get(),
                'payments' => Payment::select('id', 'name')->get(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {
        $purchase = Purchase::create([
            'item_id' => $request->item_id,
            'purchaser_id' => $request->purchaser_id,
            'status_id' => 1,
            'ship_address' => $request->ship_address,
            'payment_id' => $request->payment_id,
            'email' => $request->email,
        ]);

        return Redirect::route('thanks', ['purchase' => $purchase->id]);
    }

    /**
     * サンクスページを表示する。
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function thanks(Purchase $purchase)
    {
        return Inertia::render(
            'Purchases/Thanks',
            [
                'purchase' => Purchase::select('item_id', 'purchaser_id', 'ship_address', 'payment_id', 'email', 'created_at')
                    ->with('item.user.userImage', 'user', 'payment')
                    ->findOrFail($purchase->id),
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        $purchase->update($request->only(['status_id']));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
