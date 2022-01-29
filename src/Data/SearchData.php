<?php

namespace App\Data;

use App\Entity\Category;

class SearchData
{
    public ?string $query = null;

    public ?int $page = null;

    public ?Category $category = null;

    public ?int $distance = null;

    public ?array $reviews = null;

    public ?string $sort = null;

    public const SORT_TYPE_LOCATION = 'location';

    public const SORT_TYPE_REVIEW = 'review';

    public const SORT_TYPE_NAME = 'name';

    /**
     * @return null|string
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @return int|null
     */
    public function getDistance(): ?int
    {
        return $this->distance;
    }

    /**
     * @return array|null
     */
    public function getReviews(): ?array
    {
        return $this->reviews;
    }

    /**
     * @return null|string
     */
    public function getSort(): ?string
    {
        return $this->sort;
    }

    /**
     * @param null|string $query
     */
    public function setQuery(?string $query): void
    {
        $this->query = $query;
    }

    /**
     * @param int|null $page
     */
    public function setPage(?int $page): void
    {
        $this->page = $page;
    }
}