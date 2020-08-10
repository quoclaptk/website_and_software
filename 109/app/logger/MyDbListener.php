<?php

namespace Modules\Logger;

use Phalcon\Db\Profiler;
use Phalcon\Events\Event;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File;

class MyDbListener
{
    protected $profiler;

    protected $logger;

    protected $logFile;

    /**
     * Creates the profiler and starts the logging
     */
    public function __construct()
    {
        $this->profiler = new Profiler();
        $file = $this->logFile = "../app/logs/sqlSlow.log";
        if (!file_exists($file)) {
            fopen($file, "w+");
        }
        
        $this->logger   = new File($file);
    }

    /**
     * This is executed if the event triggered is 'beforeQuery'
     */
    public function beforeQuery(Event $event, $connection)
    {
        $this->profiler->startProfile(
            $connection->getSQLStatement()
        );
    }

    /**
     * This is executed if the event triggered is 'afterQuery'
     */
    public function afterQuery(Event $event, $connection)
    {
        if (!empty($this->getProfiler()->getProfiles()) && count($this->getProfiler()->getProfiles()) > 0) {
            $data = file($this->logFile);
            $sqlQueryLastLog = null;
            if (count($data) >  1) {
                $line = $data[count($data) - 1];
                $lineContent = explode('-', $line);
                if (isset($lineContent[1])) {
                    $sqlQueryLastLog = trim($lineContent[1]);
                }
            }

            $profiles = $this->getProfiler()->getProfiles();
            foreach ($profiles as $key => $profile) {
                $sqlStatement=  trim($profile->getSQLStatement());
                // echo 'Final Time: ', date('Y-m-d H:i:s', $profile->getFinalTime()), "<br>";
                // end time
                // echo date('Y-m-d H:i:s', $profile->getInitialTime());

                // custom log slow query
                if ($profile->getTotalElapsedSeconds() > 0.5) {
                    if (isset($profiles[$key - 1]) && $sqlStatement != $profiles[$key - 1]->getSQLStatement() && $sqlStatement != $sqlQueryLastLog) {
                        $this->logger->log('- ' . $profile->getSQLStatement() . ' - ' . $profile->getTotalElapsedSeconds());  
                    }
                }
            }
        }
        
        /*$this->logger->log(
            $connection->getSQLStatement(),
            Logger::INFO
        );*/

        $this->profiler->stopProfile();
    }

    public function getProfiler()
    {
        return $this->profiler;
    }
}
