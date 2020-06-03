<?php

declare(strict_types=1);

namespace App\Http\View\Composers;

use Illuminate\View\View;

class AppViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('searchTooltip', $this->getSearchTooltip());
    }

    /**
     * Get the search tooltip text.
     *
     * @return string
     */
    protected function getSearchTooltip(): string
    {
        return <<<'EOF'
Press / to focus this field.
This will search in the asset's title, blurb and tags.
This field supports search string syntax. Examples:

Hello world  —  Search for "Hello" and "world" individually
"Hello world"  —  Perform an exact match instead of matching words individually
score >= 3  —  Show assets with a score greater than or equal to 3
license = MIT  —  Show assets licensed under the MIT license (use SPDX identifiers)
updated_at > 2020-01-01  —  Show assets updated after January 1 2020
EOF;
    }
}
