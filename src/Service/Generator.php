<?php

namespace App\Service;

use App\Repository\RequestRepository;

class Generator
{

    public function __construct(
        private RequestRepository $requestRepository
    )
    {
    }

    /**
     * @return string
     */
    public function generateRequestReference(): string
    {
        $reference = $this->generateReference(3, 5);
        return $this->requestRepository->findBy(['reference' => $reference])
            ? $this->generateRequestReference()
            : $reference;
    }

    /**
     * @param $letters number of letter
     * @param $digits number of digit
     * @return string
     */
    public function generateReference($letters, $digits): string
    {
        $res = [];
        for ($i = 0; $i < $letters; $i++) {
            $res[] = mb_strtoupper(chr(97 + rand(0, 25)));
        }
        for ($i = 0; $i < $digits; $i++) {
            $res[] = rand(0, 9);
        }

        return implode('', $res);
    }
}