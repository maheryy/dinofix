<?php

namespace App\Twig\Functions;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Data extends AbstractExtension
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_popular_categories', [$this, 'getPopularCategories']),
            new TwigFunction('get_frequent_keywords', [$this, 'getFrequentKeywords']),
        ];
    }

    // TODO : change these hardcoded values
    public function getPopularCategories(?int $maxResults = null): ?array
    {
        //return $this->categoryRepository->findPopularCategories($maxResults);
        return [
          ['name' => 'Moteur défaillant'],
          ['name' => 'Ailes déformés'],
          ['name' => 'Epaules trop large'],
          ['name' => 'Manque d\'hydratation'],
          ['name' => 'Trouble de la communication'],
          ['name' => 'Manque d\'hygiène'],
          ['name' => 'Crises d\'anxiété'],
        ];
    }

    // TODO : change these hardcoded values
    public function getFrequentKeywords(): ?array
    {
        return [
            ['term' => 'Blessure pattes avant'],
            ['term' => 'Ligaments croisés'],
            ['term' => 'Massage dinosaure'],
            ['term' => 'Petits soins dinosaure'],
        ];
    }

}