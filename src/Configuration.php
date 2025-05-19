<?php

namespace BlessingSkin\ProxyIP;

use App\Services\Hook;
use Blessing\Filter;
use Illuminate\Support\Facades\Request;
use Option;

class Configuration
{
    public function render()
    {
        // 获取当前 IP 地址
        $ip = Request::ip();

        // 应用 client_ip 过滤器获取真实 IP
        $filter = app(Filter::class);
        $realIp = $filter->apply('client_ip', $ip);

        // 创建配置表单
        $form = Option::form(
            'blessing-skin-proxy-ip',
            trans('BlessingSkin\ProxyIP::general.config.title'),
            function ($form) use ($ip, $realIp) {
                // 显示当前 IP 地址
                $form->text(
                    'current_ip',
                    trans('BlessingSkin\ProxyIP::general.config.current-ip.title')
                )->value($ip)
                ->disabled()
                ->description(trans('BlessingSkin\ProxyIP::general.config.current-ip.description'));

                // 显示检测到的真实 IP 地址
                $form->text(
                    'real_ip',
                    trans('BlessingSkin\ProxyIP::general.config.real-ip.title')
                )->value($realIp)
                ->disabled()
                ->description(trans('BlessingSkin\ProxyIP::general.config.real-ip.description'));

                // 配置选项
                $form->textarea(
                    'blessing-skin-proxy-ip_trusted-proxies',
                    trans('BlessingSkin\ProxyIP::general.config.trusted-proxies.title')
                )->description(trans('BlessingSkin\ProxyIP::general.config.trusted-proxies.description'));

                $form->checkbox(
                    'blessing-skin-proxy-ip_proxy-types_proxy_headers',
                    trans('BlessingSkin\ProxyIP::general.config.proxy-types.proxy-headers')
                )->label(trans('BlessingSkin\ProxyIP::general.config.proxy-types.proxy-headers'));

                $form->checkbox(
                    'blessing-skin-proxy-ip_proxy-types_cloudflare',
                    trans('BlessingSkin\ProxyIP::general.config.proxy-types.cloudflare')
                )->label(trans('BlessingSkin\ProxyIP::general.config.proxy-types.cloudflare'));

                $form->checkbox(
                    'blessing-skin-proxy-ip_proxy-types_incapsula',
                    trans('BlessingSkin\ProxyIP::general.config.proxy-types.incapsula')
                )->label(trans('BlessingSkin\ProxyIP::general.config.proxy-types.incapsula'));

                $form->checkbox(
                    'blessing-skin-proxy-ip_proxy-types_custom',
                    trans('BlessingSkin\ProxyIP::general.config.proxy-types.custom')
                )->label(trans('BlessingSkin\ProxyIP::general.config.proxy-types.custom'));

                $form->textarea(
                    'blessing-skin-proxy-ip_custom-headers',
                    trans('BlessingSkin\ProxyIP::general.config.custom-headers.title')
                )->description(trans('BlessingSkin\ProxyIP::general.config.custom-headers.description'));
            }
        )->handle();

        return view('BlessingSkin\ProxyIP::config', [
            'form' => $form
        ]);
    }
}
