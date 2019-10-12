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
        // swoft2.0 推荐用sgo函数 用saber提交数据
        sgo(function () use ($url, $data, $headers) { //改为使用Saber来替代httpClient上报
            $urlArr = parse_url($url);
            $saber = \Swlib\Saber::create([
                'base_uri' => $urlArr['scheme'].'://'.$urlArr['host'],
                'timeout' => 2,
                'headers' => array_merge($headers,['Expect' => ''])
            ]);
            $saber->post($urlArr['path'], $data);
        });
        return true;
    }

}
