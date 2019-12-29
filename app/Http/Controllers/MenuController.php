<?php

namespace App\Http\Controllers;

use App\Menu;
//use App\Item;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Store menu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->json()->all();

        $menu = new Menu;
        $menu->field = $data['field'];
        $menu->max_depth = $data['max_depth'];
        $menu->max_children = $data['max_children'];
        $menu->save();

        return response($menu, 201, ['Location' => action('MenuController@show', $menu->id)]);
    }

    /**
     * Display menu.
     *
     * @param  mixed  $menu
     * @return \Illuminate\Http\Response
     */
    public function show($menu)
    {
        $menu = Menu::find($menu);

        return response($menu, 200);
    }

    /**
     * Update menu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $menu)
    {
        $data = $request->json()->all();

        $menu = Menu::find($menu);
        $menu->field = $data['field'];
        $menu->max_depth = $data['max_depth'];
        $menu->max_children = $data['max_children'];
        $menu->save();

        return response($menu, 201);
    }

    /**
     * Remove menu.
     *
     * @param  mixed  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy($menu)
    {

       // Item::whereMenuId($menu)->delete();
        $destroyed = Menu::destroy($menu);

        return response(null, $destroyed ? 204 : 404);
    }
}
