<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ItemChildrenController extends Controller
{

    /**
     * Create item's children.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $item
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $item)
    {

        //$item = !$item instanceof Item ? Item::find($item) : $item;
        $item = Item::find($item);
        $data = $request->json()->all();
        $this->addChildren($data, $item->id);
        // Would probably be better to let addChildren return the children
        $children = Item::whereParentId($item->id)->with('children')->get();

        return response($children, 201, ['Location' => action('ItemChildrenController@show', $item->id)]);
    }

    /**
     * Get all item's children.
     *
     * @param  mixed  $item
     * @return \Illuminate\Http\Response
     */
    public function show($item)
    {
        $children = Item::whereParentId($item)->with('children')->get();

        return response($children, 200);
    }

    /**
     * Remove all children.
     *
     * @param  mixed  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($item)
    {
        $deleted = Item::whereParentId($item)->delete();

        return response(null, $deleted ? 204 : 404);
    }

    /**
     * Add children to item
     *
     * @param  array    $itemsToAdd
     * @param  integer  $parentId
     */
    private function addChildren($itemsToAdd, $parentId)
    {
        foreach ($itemsToAdd as $item) {
            $newItem = Item::create([
                'field' =>  $item['field'],
                'parent_id' => $parentId
            ]);

            if (isset($item['children']) && !empty($item['children'])) {
                $this->addChildren($item['children'], $newItem->id);
            }
        }
    }
}
