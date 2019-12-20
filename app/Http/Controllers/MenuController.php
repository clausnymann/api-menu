<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $menu = new Menu;
        $menu->field = $request->field;
        $menu->max_depth = $request->max_depth;
        $menu->max_children = $request->max_children;
        $menu->save();

        return response($menu->only(['field', 'max_depth', 'max_children']), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed  $menu
     * @return \Illuminate\Http\Response
     */
    public function show($menu)
    {
        $menu = Menu::find($menu, ['field', 'max_depth', 'max_children']);

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

        $menu = Menu::find($menu);
        $menu->field = $request->field;
        $menu->max_depth = $request->max_depth;
        $menu->max_children = $request->max_children;
        $menu->save();

        return response($menu->only(['field', 'max_depth', 'max_children']), 201);
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

        if($destroyed){
            return response(null, 204);
        }else{
            return response(null, 404);
        }

    }
}
