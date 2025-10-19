<?php
namespace Zulkarnen;

class MoodleAuth
{
    public static function fetchToken(string $baseUrl, string $username, string $password, string $serviceName): string
    {
        $url = rtrim($baseUrl, '/') . '/login/token.php';
        $postData = http_build_query([
            'username' => $username,
            'password' => $password,
            'service'   => $serviceName,  // ← This must be 'service'
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $res = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($res, true);
        if (isset($data['token'])) return $data['token'];
        throw new \Exception($data['error'] ?? 'Failed to fetch token');
    }
}