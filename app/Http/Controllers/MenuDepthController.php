<?php

namespace App\Http\Controllers;

use App\Item;

class MenuDepthController extends Controller
{

    /**
     * Get depth of menu.
     *
     * @param  mixed  $menu
     * @return \Illuminate\Http\Response
     */
    public function show($menu)
    {
        $items = Item::with('children')->whereMenuId($menu)->get();
        $maxDepth = $this->getDepth($items);

        return response($maxDepth, 201);
    }

    private function getDepth($items, $maxDepth = 1, $depth = 1)
    {
        if (empty($items[0])) {
            return null;
        }
        if ($depth > $maxDepth) {
            $maxDepth = $depth;
        }
        if (is_null($items[0]->parent_id)) {
            $depth = 1;
        }

        foreach ($items as $item) {
            if (isset($item->children[0])) {
                if (!isset($depthIncremented)) {
                    $depth ++;
                    $depthIncremented = true;
                }
                $maxDepth = $this->getDepth($item->children, $maxDepth, $depth);
            }
        }

        return $maxDepth;
    }
}
