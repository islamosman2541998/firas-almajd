<?php

namespace App\Support;

use App\Services\SiteRepository;

/**
 * Resolves the cached menu tree into render-ready links for the current
 * locale and request (url, active state, dropdown children).
 */
class NavBuilder
{
    public function __construct(protected SiteRepository $repository) {}

    public function items(string $location = 'header'): array
    {
        $current = rtrim(url()->current(), '/');

        $resolve = function (array $items) use (&$resolve, $current) {
            return array_map(function (array $item) use (&$resolve, $current) {
                $url = $item['link'] ? link_url($item['link']) : null;
                $children = $resolve($item['children']);
                $selfActive = $url && ! str_starts_with($url, '#') && rtrim(strtok($url, '#'), '/') === $current;

                return [
                    'title' => tval($item['title']),
                    'url' => $url,
                    'new_tab' => $item['new_tab'],
                    'children' => $children,
                    'active' => $selfActive,
                    'open' => $selfActive || collect($children)->contains(fn ($child) => $child['active'] || $child['open']),
                ];
            }, $items);
        };

        return $resolve($this->repository->menu($location));
    }
}
