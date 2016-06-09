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

use TheliaMailCatcher\Plugin\SwiftEventListenerPlugin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\MailTransporterEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Mailer\MailerFactory;
use Thelia\Model\ConfigQuery;

/**
 * Class MailerListener
 * @package TheliaMailCatcher\EventListener
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class MailerListener implements EventSubscriberInterface
{
    /**
     * @param MailTransporterEvent $event
     */
    public function addPlugin(MailTransporterEvent $event)
    {
        if (!$event->hasTransporter()) {
            $event->setMailerTransporter(
                ConfigQuery::isSmtpEnable() ? $this->configureSmtp() : \Swift_MailTransport::newInstance()
            );
        }

        /** @var MailerFactory $mailer */
        $event->getTransporter()->registerPlugin(new SwiftEventListenerPlugin());
    }

    /**
     * @return \Swift_SmtpTransport
     */
    protected function configureSmtp()
    {
        $smtpTransporter = \Swift_SmtpTransport::newInstance(ConfigQuery::getSmtpHost(), ConfigQuery::getSmtpPort());

        if (ConfigQuery::getSmtpEncryption()) {
            $smtpTransporter->setEncryption(ConfigQuery::getSmtpEncryption());
        }
        if (ConfigQuery::getSmtpUsername()) {
            $smtpTransporter->setUsername(ConfigQuery::getSmtpUsername());
        }
        if (ConfigQuery::getSmtpPassword()) {
            $smtpTransporter->setPassword(ConfigQuery::getSmtpPassword());
        }
        if (ConfigQuery::getSmtpAuthMode()) {
            $smtpTransporter->setAuthMode(ConfigQuery::getSmtpAuthMode());
        }
        if (ConfigQuery::getSmtpTimeout()) {
            $smtpTransporter->setTimeout(ConfigQuery::getSmtpTimeout());
        }
        if (ConfigQuery::getSmtpSourceIp()) {
            $smtpTransporter->setSourceIp(ConfigQuery::getSmtpSourceIp());
        }

        return $smtpTransporter;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::MAILTRANSPORTER_CONFIG => ['addPlugin', 128]
        ];
    }
}
