<?php

namespace App\Twig\Functions;

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
        ];
    }

    public function isLoggedIn(): bool
    {
        return (bool)$this->security->getUser();
    }
}