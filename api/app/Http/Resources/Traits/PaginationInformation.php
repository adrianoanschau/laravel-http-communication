<?php

namespace App\Http\Resources\Traits;

trait PaginationInformation {
    public function paginationInformation($request, $paginated, $default)
    {
        $append = "&per_page={$paginated['per_page']}";

        $default['links'] = $this->getLinks($default['links'], $append);
        $default['meta']['links'] = $this->getMetaLinks($default['meta']['links'], $append);

        return $default;
    }

    private function getLinks($defaultLinks, string $append)
    {
        [
            'first' => $first,
            'last' => $last,
            'prev' => $prev,
            'next' => $next
        ] = $defaultLinks;

        $defaultLinks['first'] = "{$first}{$append}";
        $defaultLinks['last'] = "{$last}{$append}";

        if (!is_null($prev)) {
            $defaultLinks['prev'] = "{$prev}{$append}";
        }

        if (!is_null($next)) {
            $defaultLinks['next'] = "{$next}{$append}";
        }

        return $defaultLinks;
    }

    private function getMetaLinks(array $defaultMetaLinks, string $append)
    {
        return array_map(function ($link) use ($append) {
            if (!is_null($link['url'])) {
                $link['url'] = "{$link['url']}{$append}";
            }

            return $link;
        }, $defaultMetaLinks);
    }
}
