<?php

namespace App\Http\Resources\Traits;

trait PaginationInformation
{
    /**
     * Provide paginationInformation for a Illuminate\Http\Resources\Json\ResourceCollection;
     *
     * @param  Illuminate\Http\Request $request
     * @param  array<string, int> $paginated
     * @param  array<string, mixed> $default
     * @return array<string, mixed>
     */
    public function paginationInformation($request, $paginated, $default)
    {
        $append = "&per_page={$paginated['per_page']}";

        $default['links'] = $this->getLinks($default['links'], $append);
        $default['meta']['links'] = $this->getMetaLinks($default['meta']['links'], $append);

        return $default;
    }

    /**
     * Provide transformed links for a Illuminate\Http\Resources\Json\ResourceCollection
     *
     * @param  array<string, mixed> $defaultLinks
     * @param  string $append
     * @return array<string, mixed>
     */
    private function getLinks(array $defaultLinks, string $append)
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

    /**
     * Provide transformed meta for a Illuminate\Http\Resources\Json\ResourceCollection
     *
     * @param  array<string, mixed> $defaultMetaLinks
     * @param  string $append
     * @return array<string, mixed>
     */
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
