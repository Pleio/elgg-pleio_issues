<?php

class GithubClient {
    
    protected $url = 'https://api.github.com';
    protected $debug = false;

    public function setCredentials($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function setDebug($value) {
        $this->debug = $value;
    }

    public function request($url, $method, $data = null) {
        $c = curl_init();

        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "$this->username:$this->password");
        curl_setopt($c, CURLOPT_USERAGENT, "github-api");

        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_TIMEOUT, 10);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_bool($value)) {
                    $data[$key] = $value ? 'true' : 'false';
                }
            }
        }

        if ($this->debug) {
            curl_setopt($c, CURLOPT_VERBOSE, true);
        }

        switch ($method) {
            case 'GET':
                curl_setopt($c, CURLOPT_HTTPGET, true);
                if(count($data))
                    $url .= '?' . http_build_query($data);
                break;
            case 'POST':
                curl_setopt($c, CURLOPT_POST, true);
                if(count($data))
                    curl_setopt($c, CURLOPT_POSTFIELDS, $data);
                break;
            case 'PUT':
                curl_setopt($c, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($c, CURLOPT_PUT, true);
                
                $headers = array(
                    'X-HTTP-Method-Override: PUT', 
                    'Content-type: application/x-www-form-urlencoded'
                );
                
                if(count($data))
                {
                    $content = json_encode($data, JSON_FORCE_OBJECT);
                
                    $fileName = tempnam(sys_get_temp_dir(), 'gitPut');
                    file_put_contents($fileName, $content);
     
                    $f = fopen($fileName, 'rb');
                    curl_setopt($c, CURLOPT_INFILE, $f);
                    curl_setopt($c, CURLOPT_INFILESIZE, strlen($content));
                }
                curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
                break;
            case 'PATCH':
            case 'DELETE':
                curl_setopt($c, CURLOPT_CUSTOMREQUEST, $method);
                if ( $data )
                {
                    curl_setopt($c, CURLOPT_POST, true);
                    curl_setopt($c, CURLOPT_POSTFIELDS, $data);
                }
                break;                
        }

        curl_setopt($c, CURLOPT_URL, $this->url . $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        
        $response = curl_exec($c);
        curl_close($c);

        if ($this->debug) {
            echo "Response: " . $response;
        }

        return json_decode($response);
    }
}