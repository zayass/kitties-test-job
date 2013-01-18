<?php
namespace Application\Model;

use Zend\Authentication\AuthenticationService;
use Zend\Cache\Exception\LogicException;

class KittyService {
    private $table,
            $auth_service;

    public function __construct(AuthenticationService $auth_service, KittyTable $table) {
        $this->auth_service = $auth_service;
        $this->table = $table;
    }

    public function createForUser() {
        if (!$this->auth_service->hasIdentity()) {
            throw new LogicException("User must be logged in");
        }

        $kitty = new Kitty();
        $user = $this->auth_service->getIdentity();

        $kitty->setUserId($user->getId());

        return $kitty;
    }

    public function getBigRandom() {
        $width  = rand(1200, 1500);
        $height = rand(500, 700);

        $kitty = new Kitty();

        $kitty->setWidth($width)
              ->setHeight($height);

        return $kitty;
    }

    public function getRandomSet() {
        for ($i = 0; $i < 12; $i++) {
            $width = 250;
            $height = rand(200, 500);

            $kitties[] = new Kitty($width, $height);
        }

        return $kitties;
    }
}
