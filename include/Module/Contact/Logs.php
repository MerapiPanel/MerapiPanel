<?php

namespace MerapiPanel\Module\Contact;

use MerapiPanel\Box\Module\__Fragment;

class Logs extends __Fragment
{

    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }




    function write($client_ip, $contact_id)
    {

        $file = __DIR__ . "/logs/" . date("Y-m-d") . ".log";
        $logs = $this->read(date("Y-m-d"));

        $last = end($logs);
        if ($last['client_ip'] == $client_ip && date("Y-m-d H:i:s", strtotime($last['date'])) > date("Y-m-d H:i:s", strtotime("-10 minutes")))
        // prevent repeated writes in 10 minutes
        {
            return false;
        }

        file_put_contents($file, "[" . date("Y-m-d H:i:s") . "] " . $client_ip . " - " . $contact_id . "\n", FILE_APPEND);

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
            // Extract date, client IP, and contact_id from each line
            preg_match('/\[(.*?)\] (.*?) - (.*?)$/', $line, $matches);
            $date = trim($matches[1] ?? '');
            $clientIp = trim($matches[2] ?? '');
            $contact_id = trim($matches[3] ?? '');

            // Create an array representing the log entry
            $logEntry = [
                "date" => $date,
                "client_ip" => $clientIp,
                "contact_id" => $contact_id
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