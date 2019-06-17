<?php
namespace Gaodeng\SwoftSentry;

use Monolog\Handler\RavenHandler;
use Monolog\Logger;
use Swoft\App;

class SentryHandler extends RavenHandler
{

    /**
     * @var array 输出包含日志级别集合
     */
    protected $levels = [];

    /**
     * @var string sentry dsn
     */
    protected $dsn = "";


    /**
     * @param int          $level       The minimum logging level at which this handler will be triggered
     * @param bool         $bubble      Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        $this->setLevel($level);
        $this->bubble = $bubble;

    }

    /**
     * {@inheritdoc}
     */
    public function handleBatch(array $records)
    {
//        print_r($records);
        $records = $this->recordFilter($records);
        if (empty($records)) {
            return true;
        }

        //判断是否协程上下文
        if(is_null($this->ravenClient)){
            $options = [
                'dsn' => $this->dsn,
                'curl_method' => App::isCoContext() ? 'co' : 'sync'
            ];
            $this->ravenClient = new RavenClient($options);
        }

        foreach($records as $record){
            $this->write($record);
        }
    }


    /**
     * 记录过滤器
     *
     * @param array $records 日志记录集合
     *
     * @return array
     */
    private function recordFilter(array $records)
    {
        $messages = [];
        foreach ($records as $record) {
            if (!isset($record['level'])) {
                continue;
            }
            if (!$this->isHandling($record)) {
                continue;
            }

            $record = $this->processRecord($record);
            $record['formatted'] = $this->getFormatter()->format($record);

            $messages[] = $record;
        }
        return $messages;
    }


    /**
     * check是否输出日志
     *
     * @param array $record
     *
     * @return bool
     */
    public function isHandling(array $record)
    {
        if (empty($this->levels)) {
            return true;
        }
        return in_array($record['level'], $this->levels);
    }




}