<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Validator;

class MenuItemController extends Controller
{
    /**
     * Create menu items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $menu
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $menu)
    {

        $data = $request->json()->all();

        /*
         * The laravel validation does not seem to work properly for nested requests. Maybe it could be achieved by preserving the keys
         * and flattening the nested request.
         */
        $validator = Validator::make($data, [
            '*.field' => 'required|min:2|max:30',
        ]);

        if ($validator->fails()) {
            return response(["message" => "Validation failed", "errors" => $validator->errors()], 400);
        }
        $menu = Menu::find($menu);
        $this->addItemsToMenu($menu, $data);
        $items = Item::with('children')->whereMenuId($menu->id)->whereNull('parent_id')->get();

        return response($items, 201, ['Location' => action('MenuItemController@show', $menu->id)]);
    }

    /**
     * Get all menu items.
     *
     * @param  mixed  $menu
     * @return \Illuminate\Http\Response
     */
    public function show($menu)
    {

        $items = Item::with('children')->whereMenuId($menu)->whereNull('parent_id')->get();

        return response($items, 200);
    }

    /**
     * Remove all menu items.
     *
     * @param  mixed  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy($menu)
    {

        $deleted = Item::whereMenuId($menu)->delete();

        return response(null, $deleted ? 204 : 404);
    }

    /**
     * Add items to menu
     *
     * @param  object    $menu
     * @param  array    $items
     * @param  integer  $parentId
     * @param  integer  $depth
     * @param  array    $layers
     */
    private function addItemsToMenu($menu, $items, $parentId = null, $depth = 0, $layers = array())
    {
        if (!isset($layers[$parentId])) {
            $layers[$parentId] = 0;
        }

        foreach ($items as $item) {
            if ($layers[$parentId] <= $menu->max_children) {
                $newItem = Item::create([
                    'field' =>  $item['field'],
                    'parent_id' => $parentId,
                    'menu_id' => $menu->id
                ]);
                $layers[$parentId]++;
                if (isset($item['children']) && !empty($item['children'])) {
                    if ($depth < $menu->max_depth) {
                        $depth++;
                        $this->addItemsToMenu($menu, $item['children'], $newItem->id, $depth, $layers);
                    }
                }
            }
        }
    }
}
