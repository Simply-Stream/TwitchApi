<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models;

/**
 * @template T
 * @extends TwitchDataResponse<T>
 */
readonly class TwitchPaginatedDataResponse extends TwitchDataResponse implements TwitchPaginatedResponseInterface
{
    /**
     * @TODO: Check if the pagination is dynamic in a way, that it's completely missing or just empty
     *
     * @param T               $data
     * @param Pagination|null $pagination This could be non-existing, when a page is small enough to fit into one.
     * @param int|null        $total
     */
    public function __construct(
        mixed $data,
        protected ?Pagination $pagination = null,
        protected ?int $total = null
    ) {
        parent::__construct($data);
    }

    public function getPagination(): ?Pagination
    {
        return $this->pagination;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }
}
