# BlessingSkinProxyIP

这是一个 Blessing Skin Server 插件，用于解决在各种代理环境下客户端 IP 地址获取不正确的问题。

## 背景

Blessing Skin Server 使用 Vectorface\Whip 库来获取客户端 IP 地址，但在使用 Nginx、Apache、Traefik 等代理或 Kubernetes Ingress 等环境时，可能无法正确识别真实的客户端 IP。

## 功能特点

- 支持多种代理环境：
  - 常规代理头（X-Forwarded-For, X-Real-IP 等）
  - CloudFlare CDN
  - Incapsula CDN
  - 自定义代理配置

- 可配置选项：
  - 可配置受信任的代理 IP 地址/范围（白名单）
  - 支持自定义 HTTP 头
  - 提供测试功能，验证配置是否正确

## 安装方法

1. 下载此插件的 ZIP 压缩包
2. 将压缩包上传到 Blessing Skin 的 `plugins` 目录下
3. 解压压缩包
4. 在 Blessing Skin 的管理面板中启用插件
5. 进入插件配置页面进行设置

## 配置说明

### 受信任的代理

在这里设置您的代理服务器的 IP 地址或 IP 范围，每行一个。支持以下格式：

- 单个 IP 地址：`192.168.1.1`
- CIDR 表示法：`192.168.1.0/24`
- 范围表示法：`192.168.1.1-192.168.1.10`
- 通配符：`192.168.1.*`

### 代理类型

选择您使用的代理类型，以便插件能够正确识别客户端 IP：

- **常规代理头**：使用常见的代理 HTTP 头，如 X-Forwarded-For, X-Real-IP 等
- **CloudFlare**：使用 CloudFlare 特有的 CF-Connecting-IP 头
- **Incapsula**：使用 Incapsula 特有的 Incap-Client-IP 头
- **自定义**：使用您自定义的 HTTP 头

### 自定义 HTTP 头

如果您选择了自定义代理类型，请在此处设置自定义的 HTTP 头，每行一个。例如：

```
X-My-Real-IP
X-Custom-Client-IP
```

## 常见问题

### 为什么启用插件后仍然无法获取正确的 IP 地址？

1. 请确保您已正确配置了受信任的代理 IP 地址/范围
2. 检查您的代理服务器是否正确设置了相应的 HTTP 头
3. 使用测试功能验证配置是否正确

### 如何确定我应该使用哪种代理类型？

- 如果您使用 Nginx 或 Apache 作为反向代理，选择常规代理头
- 如果您的网站使用 CloudFlare CDN，选择 CloudFlare
- 如果您的网站使用 Incapsula CDN，选择 Incapsula
- 如果您使用其他类型的代理或需要自定义 HTTP 头，选择自定义

## 许可证

MIT License
