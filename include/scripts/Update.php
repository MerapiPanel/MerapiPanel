<?php

use Symfony\Component\Filesystem\Path;

function fetch($url, $headers = [], $cache = true)
{

    $cache_dir = __DIR__ . "/cached/";
    $cache_file = $cache_dir . base64_encode($url);

    // Ensure the cache directory exists
    if (!is_dir($cache_dir)) {
        mkdir($cache_dir, 0755, true);
    }

    if ($cache) {
        // Check if cache file exists and is older than 1 hour
        if (file_exists($cache_file) && (filectime($cache_file) > (time() - (3600 * 8)))) {
            return file_get_contents($cache_file);
        }
    }

    // Initialize cURL
    $ch = curl_init();

    // Default headers
    $default_headers = [
        "User-Agent: MerapiPanel Crawler"
    ];

    // Merge headers if any
    $all_headers = array_merge($default_headers, $headers);

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $all_headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // Maximum number of redirects to follow

    // Execute cURL request
    $result   = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Store cache file if request was successful
    if ($httpcode == 200) {
        if ($cache) {
            file_put_contents($cache_file, $result);
        }
    } else {
        $data = json_decode($result, 1);
        throw new Exception($data['message'] ?? "Server is currently unavailable, please try again later.", 500);
    }
    return $result;
}




function downloadLastestRelease($output, $lastest)
{
    $result = fetch($lastest['zipball_url'], [
       
    ], 0);
    file_put_contents($output, $result);
}




function fetchLastestRealese($user, $repo)
{

    $result = fetch("https://api.github.com/repos/{$user}/{$repo}/releases/latest", [
       
    ]);
    if ($result) {
        $data = json_decode($result, 1);
        if (isset($data['status']) && $data['status'] != 200) {
            throw new Exception($data['message'] ?? "Server is currently unavailable, please try again later.", 500);
        }
        return $data;
    } else return false;
}




function checkForUpdate()
{

    if (!file_exists(__DIR__ . "/../manifest.json")) {
        throw new Exception("Could't fetch current system version", 500);
    }
    $current = json_decode(file_get_contents(__DIR__ . "/../manifest.json"), true);
    $current_tag = $current['version'];

    if (json_last_error()) {
        throw new Exception("Caught an error while get current system version", 500);
    }

    $lastest = fetchLastestRealese("MerapiPanel", "MerapiPanel");
    if (!is_array($lastest)) throw new Exception("Clould't get lastest version", 500);
    $lastest_tag = $lastest['tag_name'];

    return [
        "available" => version_compare($current_tag, $lastest_tag) < 0 ? 1 : 0,
        "current" => $current,
        "lastest" => $lastest
    ];
}




function checkUpdateForModule($module_name)
{
    $module["name"] = $module_name;
    $module['location'] = Path::canonicalize(__DIR__ . "/../Module/" . $module_name);
    if (!file_exists($module['location'])) throw new Exception("Module not found or may not be installed", 500);
    if (!file_exists(Path::join($module['location'] . "/manifest.json"))) throw new Exception("Could't fecth module manifest", 500);
    $module['manifest'] = json_decode(file_get_contents(Path::join($module['location'] . "/manifest.json")), 1);
    if (!isset($module['manifest']['github']['user']) || !isset($module['manifest']['github']['repo'])) throw new Exception("Module manifest does't contains guthub repository.", 500);
    $lastest = fetchLastestRealese($module['manifest']["github"]['user'], $module['manifest']["github"]['repo']);
    if (!is_array($lastest) || !isset($lastest['tag_name'])) throw new Exception("Clould't get lastest version", 500);

    $current_tag = $module['manifest']['version'];
    $lastest_tag = $lastest['tag_name'];

    $module['lastest'] = $lastest;
    $module['update_status'] = version_compare($current_tag, $lastest_tag) < 0 ? 1 : 0;
    return $module;
}


function startModuleUpdateTask($idx, $module_name)
{
    $target_dir = Path::canonicalize(__DIR__ . "/../Module/$module_name/");
    if (!file_exists($target_dir)) throw new Exception("Module $module_name not found!");
    if (!file_exists(Path::join($target_dir, "manifest.json"))) throw new Exception("Could't fecth module manifest", 500);
    $manifest = json_decode(file_get_contents(Path::join($target_dir, "manifest.json")), 1);
    if (!isset($manifest['github']['user']) || !isset($manifest['github']['repo'])) throw new Exception("Module manifest does't contains guthub repository.", 500);
    $lastest = fetchLastestRealese($manifest["github"]['user'], $manifest["github"]['repo']);

    include_once __DIR__ . "/update/taskHandler.php";
    return startTask($idx, $target_dir, $lastest);
}
