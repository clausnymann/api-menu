<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ItemController extends Controller
{
    /**
     * Store item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->json()->all();

        $item = new Item;
        $item->field = $data['field'];
        $item->save();

        return response($item, 201, ['Location' => action('ItemController@show', $item->id)]);

    }

    /**
     * Display item.
     *
     * @param  mixed  $item
     * @return \Illuminate\Http\Response
     */
    public function show($item)
    {
        $item = Item::find($item);

        return response($item, 200);
    }

    /**
     * Update item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $item)
    {
        $data = $request->json()->all();

        $item = Item::find($item);
        $item->field = $data['field'];
        $item->save();

        return response($item, 201);
    }

    /**
     * Remove item.
     *
     * @param  mixed  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($item)
    {
        $destroyed = Item::destroy($item);

        return response(null, $destroyed ? 204 : 404);
    }
}
