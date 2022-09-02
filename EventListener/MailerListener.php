<?php
/*************************************************************************************/
/*      This file is part of the module TheliaMailCatcher.                           */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace TheliaMailCatcher\EventListener;

use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mailer\Header\MetadataHeader;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Model\ConfigQuery;

/**
 * Class MailerListener
 * @package TheliaMailCatcher\EventListener
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class MailerListener implements EventSubscriberInterface
{
    public function replaceRecipients(MessageEvent $event)
    {
        $message = $event->getMessage();
        if (!$message instanceof Email) {
            return;
        }

        $emails = ConfigQuery::getNotificationEmailsList();

        if (!count($emails)) {
            $emails = [ConfigQuery::getStoreEmail()];
        }

        $message->getHeaders()->add(
            new MetadataHeader(
                "OriginalRecipient",
                implode(
                    ",",
                    array_map(
                        function (Address $address) {
                            return $address->toString();
                        },
                        $message->getTo()
                    )
                )
            )
        );

        $addresses = array_map(function ($email) {return new Address($email);}, $emails);
        $event->getEnvelope()
            ->setRecipients($addresses);

        $message->to($addresses[0]);
        for ($i = 1; $i < count($addresses); $i++) {
            $message->addTo($addresses[$i]);
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            MessageEvent::class => ['replaceRecipients', -600]
        ];
    }
}
