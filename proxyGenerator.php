<?php
set_time_limit(0);

function isProxyWorking($ip, $port, $timeout = 10) {
    $proxy = "tcp://$ip:$port";
    $url = "https://www.example.com";
    $context = stream_context_create(array(
        'http' => array(
            'proxy' => $proxy,
            'request_fulluri' => true,
            'timeout' => $timeout,
        )
    ));
    $start = microtime(true);
    $response = @file_get_contents($url, false, $context);
    $end = microtime(true);
    if ($response !== false) {
        $time = round(($end - $start) * 1000);
        $workingProxy = "$ip:$port (Response time: $time ms)\n";
        file_put_contents('working_proxies.txt', $workingProxy, FILE_APPEND);
        return "Proxy $ip:$port is working. Response time: $time ms";
    } else {
        return "Proxy $ip:$port is not working";
    }
}

function checkProxiesInRange($startIP, $endIP, $startPort, $endPort) {
    for ($ip1 = $startIP[0]; $ip1 <= $endIP[0]; $ip1++) {
        for ($ip2 = $startIP[1]; $ip2 <= $endIP[1]; $ip2++) {
            for ($ip3 = $startIP[2]; $ip3 <= $endIP[2]; $ip3++) {
                for ($ip4 = $startIP[3]; $ip4 <= $endIP[3]; $ip4++) {
                    $ip = "$ip1.$ip2.$ip3.$ip4";
                    for ($port = $startPort; $port <= $endPort; $port++) {
                        $result = isProxyWorking($ip, $port);
                        echo "$result\n";
                    }
                }
            }
        }
    }
}

$startIP = explode('.', '192.0.0.0');
$endIP = explode('.', '192.168.255.255');
$startPort = 1080;
$endPort = 1090;
checkProxiesInRange($startIP, $endIP, $startPort, $endPort);
?>
