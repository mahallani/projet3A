<?php

namespace App\Service; // ⚠️ Vérifie bien ce namespace !

use Symfony\Component\HttpFoundation\RequestStack;

class NotificationService
{
    private $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    public function sendNotification(string $message)
    {
        $notifications = $this->session->get('notifications', []);
        $notifications[] = $message;
        $this->session->set('notifications', $notifications);
    }

    public function getNotifications(): array
    {
        return $this->session->get('notifications', []);
    }
}
