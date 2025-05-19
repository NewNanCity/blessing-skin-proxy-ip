<?php

use App\Services\Hook;
use Blessing\Filter;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Log;
use Vectorface\Whip\Whip;

return function (Dispatcher $events, Filter $filter) {
    // 不再需要注册管理面板菜单项

    // 不再需要注册路由

    // 注册 client_ip 过滤器
    $filter->add('client_ip', function ($ip) {
        try {
            // 设置 Whip 方法
            $methods = Whip::REMOTE_ADDR;
            if (option('blessing-skin-proxy-ip_proxy-types_proxy_headers')) {
                $methods |= Whip::PROXY_HEADERS;
            }
            if (option('blessing-skin-proxy-ip_proxy-types_cloudflare')) {
                $methods |= Whip::CLOUDFLARE_HEADERS;
            }
            if (option('blessing-skin-proxy-ip_proxy-types_incapsula')) {
                $methods |= Whip::INCAPSULA_HEADERS;
            }

            // 设置白名单
            $whitelist = [];
            $trustedProxies = option('blessing-skin-proxy-ip_trusted-proxies', '');
            if (!empty($trustedProxies)) {
                $trustedProxiesArray = array_filter(array_map('trim', explode("\n", $trustedProxies)));

                // 分离 IPv4 和 IPv6 地址
                $ipv4Proxies = [];
                $ipv6Proxies = [];

                foreach ($trustedProxiesArray as $proxy) {
                    if (strpos($proxy, ':') !== false) {
                        $ipv6Proxies[] = $proxy;
                    } else {
                        $ipv4Proxies[] = $proxy;
                    }
                }

                // 添加到白名单
                if (!empty($ipv4Proxies)) {
                    $whitelist[Whip::PROXY_HEADERS][Whip::IPV4] = $ipv4Proxies;
                    $whitelist[Whip::CLOUDFLARE_HEADERS][Whip::IPV4] = $ipv4Proxies;
                    $whitelist[Whip::INCAPSULA_HEADERS][Whip::IPV4] = $ipv4Proxies;
                }

                if (!empty($ipv6Proxies)) {
                    $whitelist[Whip::PROXY_HEADERS][Whip::IPV6] = $ipv6Proxies;
                    $whitelist[Whip::CLOUDFLARE_HEADERS][Whip::IPV6] = $ipv6Proxies;
                    $whitelist[Whip::INCAPSULA_HEADERS][Whip::IPV6] = $ipv6Proxies;
                }
            }

            // 创建 Whip 实例
            $whip = new Whip($methods, $whitelist);

            // 添加自定义 HTTP 头
            $customHeaders = option('blessing-skin-proxy-ip_custom-headers', '');
            if (option('blessing-skin-proxy-ip_proxy-types_custom') && !empty($customHeaders)) {
                $customHeadersArray = array_filter(array_map('trim', explode("\n", $customHeaders)));
                foreach ($customHeadersArray as $header) {
                    $whip->addCustomHeader($header);
                }
            }

            // 获取 IP 地址
            $clientIp = $whip->getValidIpAddress();
            if ($clientIp !== false) {
                Log::debug("[ProxyIP] 成功获取客户端 IP: {$clientIp}");
                return $clientIp;
            }
        } catch (\Exception $e) {
            Log::error("[ProxyIP] 获取客户端 IP 时出错", ['exception' => $e]);
        }

        Log::debug("[ProxyIP] 使用默认 IP: {$ip}");
        return $ip;
    });
};
