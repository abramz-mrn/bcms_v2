<?php

namespace App\Services\Notifications;

class Notifier
{
    public function sendEmail(array $payload): void {}
    public function sendSms(array $payload): void {}
    public function sendWhatsapp(array $payload): void {}
}
