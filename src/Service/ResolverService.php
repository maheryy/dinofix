<?php

namespace App\Service;

use App\Repository\FixerRepository;
use App\Repository\ServiceRepository;

class ResolverService
{

    public function __construct(
        private FixerRepository   $fixerRepository,
        private ServiceRepository $serviceRepository,
    )
    {
    }


    public function getFixerExpertise($fixerId) : array
    {
        $services = $this->serviceRepository->findAllFixerServices($fixerId);

        $res = [];

        foreach ($services as $service) {
            $category = $service->getCategory();
            $dino = $service->getDino();
            $res['categories'][$category->getId()] = $category;
            $res['dinos'][$dino->getId()] = $dino;
        }

        $res['categories'] = array_unique($res['categories']);
        $res['dinos'] = array_unique($res['dinos']);

        return $res;
    }


}