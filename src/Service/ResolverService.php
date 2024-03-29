<?php

namespace App\Service;

use App\Repository\FixerRepository;
use App\Repository\ServiceRepository;

class ResolverService
{

    public function __construct(
        private FixerRepository       $fixerRepository,
        private ServiceRepository     $serviceRepository,
    )
    {
    }

    public function getFixerExpertise($fixerId): array
    {
        $res = [];
        $services = $this->serviceRepository->findAllFixerServices($fixerId);

        foreach ($services as $service) {
            if ($category = $service?->getCategory()) {
                $res['categories'][$category->getId()] = $category;
            }
            if ($dino = $service?->getDino()) {
                $res['dinos'][$dino->getId()] = $dino;
            }
        }

        return $res;
    }
}