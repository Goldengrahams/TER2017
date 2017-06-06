<?php

namespace HLIN601\UserBundle\Controller;

class NotificationController extends Controller
{

    /**
     * @Route("/send-notification", name="send_notification")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendNotification(Request $request)
    {
        $manager = $this->get('mgilet.notification');
        $notif = $manager->generateNotification('Hello world !');
        $notif->setMessage('This a notification.');
        $notif->setLink('http://symfony.com/');
        $manager->addNotification($this->getUser(), $notif);

        // or the one-line method :
        // $manager->createNotification($this->getUser(), 'Notification subject','Some random text','http://google.fr');

        return $this->redirectToRoute('homepage');
    }
}