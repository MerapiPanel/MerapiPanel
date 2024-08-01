<?php


function fetch($url, $headers = [])
{
    $cache_dir = __DIR__ . "/cached/";
    $cache_file = $cache_dir . base64_encode($url);

    // Ensure the cache directory exists
    if (!is_dir($cache_dir)) {
        mkdir($cache_dir, 0755, true);
    }

    // Check if cache file exists and is older than 1 hour
    if (file_exists($cache_file) && (filectime($cache_file) > (time() - 3600))) {
        return file_get_contents($cache_file);
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
    curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . "/cookies.txt"); // Cookie storage
    curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . "/cookies.txt");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    // Execute cURL request
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Store cache file if request was successful
    if ($httpcode == 200) {
        file_put_contents($cache_file, $result);
    }

    return $result;
}


function fetchModules()
{
    $endpoint = "https://api.baserow.io/api/database/rows/table/331779/?user_field_names=true";
    $ch = curl_init();

    $headers = [
        "User-Agent: MerapiPanel Crawler",
        "Authorization: Token SFj4p9blklIGCeKjxbqkBTgDxKL05VkP"
    ];
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 200) {
        return json_decode($result, true);
    } else {
        return null;
    }
}

function fetchManifest($user, $repo)
{
    $endpoint = "https://raw.githubusercontent.com/{$user}/{$repo}/main/manifest.json";
    $ch = curl_init();

    $headers = [
        "User-Agent: MerapiPanel Crawler"
    ];
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 200) {
        return json_decode($result, true);
    } else {
        return null;
    }
}



function fetchLastestRealese($user, $repo)
{
    $result = fetch("https://api.github.com/repos/{$user}/{$repo}/releases/latest");
    if ($result) return json_decode($result, 1);
    else return false;
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
