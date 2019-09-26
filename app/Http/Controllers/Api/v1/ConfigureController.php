<?php

namespace App\Http\Controllers\Api\v1;

use App\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigureController extends Controller
{
    /**
     * Returns the list of available categories (used for editor integration).
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'type' => 'nullable|string|in:any,addon,project',
        ]);

        if (! isset($validated['type'])) {
            // Default to 'addon' for compatibility with the old asset library
            $validated['type'] = 'addon';
        }

        switch ($validated['type']) {
            case 'addon':
                $typeId = Asset::CATEGORY_TYPE_ADDONS;
                break;
            case 'project':
                $typeId = Asset::CATEGORY_TYPE_PROJECTS;
                break;
            default:
                // 'any'
                $typeId = -1;
                break;
        }

        $categories = [];
        foreach (range(0, Asset::CATEGORY_MAX - 1) as $categoryId) {
            if ($validated['type'] === 'any' || Asset::getCategoryType($categoryId) === $typeId) {
                $categories[$categoryId] = [
                    'id' => $categoryId,
                    'name' => Asset::getCategoryName($categoryId),
                    'type' => Asset::getCategoryType($categoryId),
                ];
            }
        }

        return [
            'categories' => $categories,
        ];
    }
}
