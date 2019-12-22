<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Item;
use Illuminate\Http\Request;

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
        $menu = Menu::find($menu);
        $data = $request->json()->all();
        $this->addItemsToMenu($menu, $data);
        $items = Item::with('children')->whereMenuId($menu->id)->get();

        return response($items, 201);
    }

    /**
     * Get all menu items.
     *
     * @param  mixed  $menu
     * @return \Illuminate\Http\Response
     */
    public function show($menu)
    {
        return Item::with('children')->whereMenuId($menu)->get();
    }

    /**
     * Remove all menu items.
     *
     * @param  mixed  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy($menu)
    {
        $destroyed = Item::whereMenuId($menu)->delete();

        return response(null, $destroyed ? 204 : 404);
    }

    /**
     * Add items to menu
     *
     * @param  mixed    $menu
     * @param  array    $items
     * @param  integer  $parentId
     * @param  integer  $depth
     * @param  array    $layers
     */
    private function addItemsToMenu($menu, $items, $parentId = null, $depth = 0, $layers = array())
    {
        if(!isset($layers[$parentId])){
            $layers[$parentId] = 0;
        }

        foreach($items as $item){
            if($layers[$parentId] <= $menu->max_children) {
                $newItem = Item::create([
                    'field' =>  $item['field'],
                    'parent_id' => $parentId,
                    'menu_id' => is_null($parentId) ? $menu->id : null
                ]);
                $layers[$parentId]++;
                if(isset($item['children']) && !empty($item['children'])){
                    if($depth < $menu->max_depth){
                        $depth++;
                        $this->addItemsToMenu($menu, $item['children'], $newItem->id, $depth, $layers);
                    }
                }
            }
        }
    }

}
