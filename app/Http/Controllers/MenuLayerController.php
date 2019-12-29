<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use App\Item;

class MenuLayerController extends Controller
{
    /**
     * Get all menu items in a layer.
     *
     * @param mixed $menu
     * @param integer $layer
     * @return \Illuminate\Http\Response
     */
    public function show($menu, $layer)
    {
        $items = Item::with('children')->whereMenuId($menu)->whereNull('parent_id')->get();
        $itemsFromLayer = $this->getItemsFromLayer($items, $layer);

        return response(Arr::pluck($itemsFromLayer, 'field'), 201);
    }

    /**
     * Remove a layer and relink layer + 1 with layer - 1, to avoid dangling data.
     *
     * @param mixed $menu
     * @param integer $layer
     * @return \Illuminate\Http\Response
     */
    public function destroy($menu, $layer)
    {
        $items = Item::with('children')->whereMenuId($menu)->whereNull('parent_id')->get();
        $itemsFromLayer = $this->getItemsFromLayer($items, $layer);

        $itemsToDelete = [];
        foreach ($itemsFromLayer as $item) {
            if (isset($item->children[0])) {
                $updated = Item::whereIn('id', $item->children->map->only('id')->flatten()->all())->update([
                    'parent_id' => $item->parent_id,
                    'menu_id' => $item->menu_id
                ]);
                if ($updated) {
                    $itemsToDelete[] = $item->id;
                }
            } else {
                $itemsToDelete[] = $item->id;
            }
        }

        $deleted = Item::whereIn('id', $itemsToDelete)->delete();

        return response(null, $deleted ? 204 : 404);
    }


    /**
     * Get layers of items.
     *
     * @param array $items
     * @param integer $layer
     * @param array $itemsFromLayer
     * @param integer $depth
     * @return array
     */

    private function getItemsFromLayer($items, $layer, $itemsFromLayer = [], $depth = 1)
    {
        if (!isset($items[0])) {
            return [];
        }

        if (is_null($items[0]->parent_id)) {
            $depth = 1;
        }

        foreach ($items as $item) {
            if ($depth == $layer) {
                $itemsFromLayer[] = $item;
            }
            if (!empty($item->children[0])) {
                    $itemsFromLayer = $this->getItemsFromLayer($item->children, $layer, $itemsFromLayer, $depth+1);
            }
        }

        return $itemsFromLayer;
    }
}
