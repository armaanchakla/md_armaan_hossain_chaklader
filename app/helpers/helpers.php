<?php

class helper {

    /**
     * @param $url
     * @return mixed
     */
    public function curl($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * @param $status
     * @param $message
     */
    public function sendResponse($status, $message) {
        echo json_encode([
            "status"  => $status,
            "message" => $message,
        ], JSON_PRETTY_PRINT);
        die();
    }
}

return $helper = new helper();