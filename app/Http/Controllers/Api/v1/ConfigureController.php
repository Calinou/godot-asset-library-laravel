<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Asset;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configure;

class ConfigureController extends Controller
{
    /**
     * Return the list of available categories (used for editor integration).
     */
    public function index(Configure $request): array
    {
        $validated = $request->validated();

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
                $categories[] = [
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
