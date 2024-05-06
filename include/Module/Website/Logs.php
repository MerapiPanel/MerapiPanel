<?php

namespace MerapiPanel\Module\Website;

use MerapiPanel\Box\Module\__Fragment;

class Logs extends __Fragment
{

    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }




    function write($client_ip, $page_path, $page_title = "")
    {

        $file = __DIR__ . "/logs/" . date("Y-m-d") . ".log";
        $logs = $this->read(date("Y-m-d"));

        if (!empty($logs)) {


            $filtered = array_filter($logs, function ($log) use ($client_ip, $page_path) {
                return $log['client_ip'] == $client_ip
                    && preg_replace("/[^a-z0-9]/i", "", strtolower($log['page_path'])) == preg_replace("/[^a-z0-9]/i", "", strtolower($page_path));
            });

            if (count($filtered) > 0) {
                // sort by date
                array_multisort(array_column($filtered, 'date'), SORT_DESC, $filtered);
                $last = end($filtered);
                $last_date = $last['date'];

                if (strtotime($last_date) > strtotime("-10 minutes"))
                // prevent repeated writes in 10 minutes
                {
                    return false;
                }
            }

        }
        file_put_contents($file, "[" . date("Y-m-d H:i:s") . "] " . $client_ip . " - " . $page_path . " | " . $page_title . "\n", FILE_APPEND);

        $this->deleteOld(); // delete old logs
    }





    function read($date)
    {
        $date = date("Y-m-d", strtotime($date));
        $filePath = __DIR__ . "/logs/" . $date . ".log";
        if (!file_exists($filePath)) {
            return [];
        }

        // Open the file for reading
        $fileHandle = fopen($filePath, "r");

        // Initialize an empty array to store log entries
        $logEntries = [];

        // Read each line of the file
        while (($line = fgets($fileHandle)) !== false) {
            // Extract date, client IP, and page from each line
            preg_match('/\[(.*?)\] (.*?) - (.*?) | (.*?)$/', $line, $matches);
            $date = trim($matches[1] ?? '');
            $clientIp = trim($matches[2] ?? '');
            $page_path = trim($matches[3] ?? '');
            $page_title = trim($matches[4] ?? '');

            // Create an array representing the log entry
            $logEntry = [
                "date" => $date,
                "client_ip" => $clientIp,
                "page_path" => $page_path,
                "page_title" => $page_title
            ];

            // Add the log entry to the array
            $logEntries[] = $logEntry;
        }

        // Close the file handle
        fclose($fileHandle);

        // Return the array of log entries
        return $logEntries;
    }



    function readRange($start, $end)
    {

        $start = date("Y-m-d", strtotime($start));
        $end = date("Y-m-d", strtotime($end));

        $scan_date = $start;
        $data = [];

        while ($scan_date <= $end) {
            $file = __DIR__ . "/logs/" . $scan_date . ".log";
            $name = date("M d", strtotime($scan_date));
            $data[$name] = $this->read($scan_date);
            $scan_date = date("Y-m-d", strtotime($scan_date . " +1 day"));
        }

        return $data;
    }


    function delete($date)
    {
        $date = date("Y-m-d", strtotime($date));
        $file = __DIR__ . "/logs/" . $date . ".log";
        if (file_exists($file)) {
            unlink($file);
        }
    }




    function deleteOld()
    {
        $files = glob(__DIR__ . "/logs/*");
        foreach ($files as $file) {
            // delete files older than 1 months
            if (date("Y-m-d", filemtime($file)) < date("Y-m-d", strtotime("-1 months"))) {
                unlink($file);
            }
        }
    }
}