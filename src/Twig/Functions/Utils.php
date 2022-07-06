<?php

namespace App\Twig\Functions;

use App\Service\Constant;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Utils extends AbstractExtension
{
    public function __construct(private Security $security)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_logged_in', [$this, 'isLoggedIn']),
            new TwigFunction('const', [$this, 'getConstant']),
            new TwigFunction('format_phone', [$this, 'formatPhone']),
        ];
    }

    public function isLoggedIn(): bool
    {
        return (bool)$this->security->getUser();
    }

    public function formatPhone(string $phone): string
    {
        return chunk_split($phone, 2, ' ');
    }

    public function getConstant(string $constant): mixed
    {
        return constant("App\Service\Constant::$constant");
    }
}