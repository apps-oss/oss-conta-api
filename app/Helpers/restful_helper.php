<?php
if (!function_exists("data")) {
    /**
     * create an array with the json format that the mobile application will read
     *
     * @param array $data
     * @param integer $code
     * @param string $message
     * @return array|object
     */
    function data(int $code, string $message, array $data = null)
    {
        return array(
            "code"    => $code,
            "message" => $message,
            "data"    => $data
        );
      
    }
}