<?php

namespace App\Data;

use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;

class SearchData
{
    public ?string $query = null;

    public ?string $location = null;

    public ?int $page = 1;

    public ?Category $category = null;

    public ?ArrayCollection $dinos = null;

    public ?int $distance = null;

    public ?array $reviews = null;

    public ?string $sort = self::SORT_TYPE_LOCATION;

    public const SORT_TYPE_LOCATION = 'location';

    public const SORT_TYPE_REVIEW = 'review';

    public const SORT_TYPE_POPULAR = 'popular';

    public const SORT_TYPE_NAME = 'name';

    public const SORT_TYPE_PRICE = 'price';

    /**
     * @return null|string
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }


    /**
     * @return null|string
     */
    public function getLocation(): ?string
    {
        return $this->location;
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
     * @return ArrayCollection|null
     */
    public function getDinos(): ?ArrayCollection
    {
        return $this->dinos;
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
     * @param null|string $location
     */
    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    /**
     * @param int|null $page
     */
    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @param ArrayCollection|null $dinos
     */
    public function setDinos(?ArrayCollection $dinos): void
    {
        $this->dinos = $dinos;
    }

    /**
     * @param null|string $sort
     */
    public function setSort(?string $sort): void
    {
        $this->sort = $sort;
    }


}