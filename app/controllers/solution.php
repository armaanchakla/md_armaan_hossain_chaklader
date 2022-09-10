<?php

class solution extends Controller {

    public function __construct() {
        $this->config = require_once './app/config/AppConfig.php';
        $this->helper = include './app/helpers/helpers.php';
    }

    /**
     * @return mixed
     */
    public function index() {
        // data from model
        $user     = $this->model('Message');
        $contents = $user->index();

        // returning view

        return $this->view('404/index', ['message' => $contents['message']]);
    }

    public function solution_one() {

        // curl method from helper
        $response = $this->helper->curl($this->config['url']);

        // converting response to array
        $response = json_decode($response, true);
        $response = $response['data'];

        // converting resposne to exploded array
        $responseArray = explode(", ", $response);

        // grepping the specified array values
        $speedArray = preg_grep("/^speed.\d+$/", $responseArray);

        $speedNumberArray = [];
        foreach ($speedArray as $value) {
            // trimmig specific string from array value and inserting into a new empty array
            $speedNumberArray[] = trim($value, "speed=");
            $resultArray        = [];
            foreach ($speedNumberArray as $val) {
                if ($val > 60) {
                    $resultArray[] = $val;
                }
            }
        }

        echo "Total: " . count($resultArray) . PHP_EOL;
        echo "List: ";

        foreach ($resultArray as $result) {
            echo PHP_EOL . $result;
        }
    }

    public function solution_two() {

        // This problem will be solved easily with "natcasesort()" function like here:
        /*
        $array = array('0'=>'z1', '1'=>'Z10', '2'=>'z12', '3'=>'Z2', '4'=>'z3');
        natcasesort($array);
        print_r($array);
        die();
         */

        // But here I am solving the problem with my custom code:
        // Defined array
        $definedArray = $this->config['definedArray'];

        // Extracting the first (z/Z) & second (numbers) character from array value
        $characterArray = [];
        $numberArray    = [];
        foreach ($definedArray as $arr) {
            $characterArray[] = substr($arr, 0, 1);
            $numberArray[]    = substr($arr, 1);
        }

        // Sorting by asc numbers
        asort($numberArray);

        $resultArray = [];
        $i           = 0;
        foreach ($numberArray as $key => $value2) {
            $resultArray[$key] = $characterArray[$i++] . $value2;
        }

        print_r($resultArray);
    }

    public function solution_three() {
        /*
        Ip Checking Condition is based on these rules:
        [1] IPv4 addresses are written with Decimal numbers. IPv4 has four groups (separated by a dot) and in each dots there are 1-3 digits. Example: 192.168.0.1
        [2] IPv6 consists of hexadecimal digits (0-9,A, B, C, D, E, F). IPv6 has 8 groups (separated with colons) and in each colons, there are 1-4 digits. Example: 2345:0425:2CA1:0000:0000:0567:5673:23b5
         */

        // get server request params
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';

        if ($contentType != "application/json") {
            // execute failed response for content type missing
            echo json_encode([
                "status"  => "failed",
                "message" => "Content-Type should be application/json",
            ]);
            die();
        }

        // get incoming request
        $input = file_get_contents("php://input");
        if (!empty($input)) {
            $input = (array) json_decode($input);
        } else {
            $this->helper->sendResponse(false, $this->config['invalidIp']);
        }

        $ipAddress = isset($input['ip']) ? $input['ip'] : '';

        if (strpos($ipAddress, $this->config['ipV4Identifier']) !== false) { // checking Ipv4

            $explodedIp = explode(".", $ipAddress);

            // checking ip length
            $elementCount = count($explodedIp);
            if ($elementCount != 4) {
                $this->helper->sendResponse(false, $this->config['invalidIp']);
            }

            foreach ($explodedIp as $value) {
                // checking every string length
                if (strlen(trim($value)) > 3) {
                    $this->helper->sendResponse(false, $this->config['invalidIp']);
                }

                // checking numeric value for ipv4
                if (!is_numeric(trim($value))) {
                    $this->helper->sendResponse(false, $this->config['invalidIp']);
                }
            }
            $this->helper->sendResponse(true, $this->config['validIp']);

        } elseif (strpos($ipAddress, $this->config['ipV6Identifier']) !== false) { // checking Ipv6

            $explodedIp = explode(":", $ipAddress);

            // checking ip length
            $elementCount = count($explodedIp);
            if ($elementCount != 8) {
                $this->helper->sendResponse(false, $this->config['invalidIp']);
            }

            foreach ($explodedIp as $value) {
                // checking every string length
                if (strlen(trim($value)) > 4) {
                    $this->helper->sendResponse(false, $this->config['invalidIp']);
                }

                // checking Hexadecimal value for ipv6
                foreach (str_split($value) as $char) {
                    if (!in_array(strtoupper(trim($char)), $this->config['ipv6HexaChecker'])) {
                        $this->helper->sendResponse(false, $this->config['invalidIp']);
                    }
                }
            }
            $this->helper->sendResponse(true, $this->config['validIp']);

        } else {
            $this->helper->sendResponse(false, $this->config['invalidIp']);
        }
    }
}