<?php

namespace App\Data;

class SearchData
{

    public $query;

    public $page;

    public $category;

    public $availability;

    public $sort;

    public const SORT_TYPE_LOCATION = 'location';

    public const SORT_TYPE_REVIEW = 'review';

    public const SORT_TYPE_NAME = 'name';
}