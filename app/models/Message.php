<?php

class Message {

    public function index() {
        // just a simple array instead on DB data
        return [
            'message' => '404 not found!',
        ];
    }

}