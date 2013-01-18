<?php

namespace Application\Model;

class Kitty {
    private $id,
            $user_id,
            $width,
            $height;

    function __construct($width = null, $height = null) {
        $this->width = $width;
        $this->height = $height;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setHeight($height) {
        $this->height = $height;
        return $this;
    }

    public function getHeight() {
        return $this->height;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
        return $this;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function setWidth($width) {
        $this->width = $width;
        return $this;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getUrl() {
        return "http://placekitten.com/$this->width/$this->height";
    }

    public function getGrayUrl() {
        return "http://placekitten.com/g/$this->width/$this->height";
    }
}
