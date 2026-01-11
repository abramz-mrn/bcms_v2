<?php

namespace App\Services\Network;

class MikrotikClient
{
    public function testConnection(): bool
    {
        // TODO: implement RouterOS API TLS + fallback SSH
        return true;
    }

    public function provisionPppoe(array $payload): void {}
    public function provisionStaticIp(array $payload): void {}
    public function applySoftLimit(array $payload): void {}
    public function suspend(array $payload): void {}
    public function reactivate(array $payload): void {}
}
