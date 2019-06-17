<?php
namespace Gaodeng\SwoftSentry;


class RavenClient extends \Raven_Client
{

    /**
     * Send the message over http to the sentry url given
     *
     * @param string       $url     URL of the Sentry instance to log to
     * @param array|string $data    Associative array of data to log
     * @param array        $headers Associative array of headers
     */
    protected function send_http($url, $data, $headers = array())
    {
        if($this->curl_method == 'co'){//异步客户端方式
            $this->send_http_asynchronous($url, $data, $headers);
        } elseif ($this->curl_method == 'async') {
            $this->_curl_handler->enqueue($url, $data, $headers);
        } elseif ($this->curl_method == 'exec') {
            $this->send_http_asynchronous_curl_exec($url, $data, $headers);
        } else {
            $this->send_http_synchronous($url, $data, $headers);
        }
    }



    /**
     * Send a  asynchronous request to Sentry with Swoft's http client
     *
     * @param string       $url     URL of the Sentry instance to log to
     * @param array|string $data    Associative array of data to log
     * @param array        $headers Associative array of headers
     * @return bool
     */
    protected function send_http_asynchronous($url, $data, $headers){

        go(function () use ($url, $data, $headers){
            $method = 'POST';
            $client = new \Swoft\HttpClient\Client([
                'adapter' => 'coroutine'
            ]);
            // Http
           $client->request($method, '', [
                'base_uri' => $url,
                '_options' => [
                    'timeout' => 2,
                ],
                'headers' => array_merge($headers,['Expect' => '']),
            ]);
        });
        return true;
    }

}