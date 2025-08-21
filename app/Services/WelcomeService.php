<?php

namespace App\Services;

use App\Interfaces\WelcomeServiceInterface;

class WelcomeService implements WelcomeServiceInterface
{
    private static $instance = null;

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getWelcomeMessage(): string
    {
        return 'Bem-vindo ao Smart Finance!';
    }
}
