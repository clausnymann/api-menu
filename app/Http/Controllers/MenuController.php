<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Store a newly created resource in storage.
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

        return response($menu, 201);
    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     *
     * @param  mixed  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy($menu)
    {
        $destroyed = Menu::destroy($menu);

        return response(null, $destroyed ? 204 : 404);
    }
}
