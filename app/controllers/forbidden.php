<?php

class forbidden extends Controller {

    /**
     * @return mixed
     */
    public function index() {
        // getting message from model
        $message = $this->model('Message');
        $message = $message->index();

        //return view

        return $this->view('404/index', ['message' => $message['message']]);
    }
}