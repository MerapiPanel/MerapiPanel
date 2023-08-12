<?php

namespace il4mb\Mpanel\Logger;

class Logger
{


    protected $logFilePath;
    protected $dateFormat;


    /**
     * Class constructor.
     *
     * @param string $logFilePath The path to the log file (default: 'logs/app.log').
     * @param string $dateFormat The format of the timestamp in the log (default: 'Y-m-d H:i:s').
     */
    public function __construct(string $logFilePath = 'logs/app.log', string $dateFormat = 'Y-m-d H:i:s')
    {

        $this->logFilePath = $logFilePath;
        $this->dateFormat  = $dateFormat;

    }




    /**
     * Logs a message with the specified level.
     *
     * @param string $message The message to be logged.
     * @param string $level The level of the log (default is 'info').
     *
     * @return void
     */
    public function log(string $message, string $level = 'info'): void
    {
        // Get the current timestamp
        $timestamp = date($this->dateFormat);

        // Create the log line with the timestamp, level, and message
        $logLine = "[$timestamp][$level]: $message" . PHP_EOL;

        // Append the log line to the log file
        file_put_contents($this->logFilePath, $logLine, FILE_APPEND | LOCK_EX);
        
    }




    /**
     * Set the log file path.
     *
     * @param string $logFilePath The path to the log file.
     * @return void
     */
    public function setLogFilePath(string $logFilePath): void
    {

        $this->logFilePath = $logFilePath;

    }




    /**
     * Returns the path of the log file.
     * 
     * @return string The path of the log file.
     */
    public function getLogFilePath(): string
    {

        return $this->logFilePath;

    }




    
    /**
     * Sets the date format.
     *
     * @param string $dateFormat The date format to set.
     * @return void
     */
    public function setDateFormat(string $dateFormat): void
    {

        $this->dateFormat = $dateFormat;

    }




    /**
     * Get the date format.
     *
     * @return string The date format.
     */
    public function getDateFormat(): string
    {

        return $this->dateFormat;
        
    }
    
}
