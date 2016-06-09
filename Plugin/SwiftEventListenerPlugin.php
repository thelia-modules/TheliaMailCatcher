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

namespace TheliaMailCatcher\Plugin;

use Thelia\Model\ConfigQuery;

/**
 * Class SwiftEventListenerPlugin
 * @package TheliaMailCatcher\Plugin
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class SwiftEventListenerPlugin implements \Swift_Events_SendListener
{
    /**
     * @param \Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(\Swift_Events_SendEvent $evt)
    {
        $emails = ConfigQuery::getNotificationEmailsList();

        if (!count($emails)) {
            $emails = [ConfigQuery::getStoreEmail()];
        }

        $evt->getMessage()->setTo($emails);

        $evt->getMessage()->setBcc([]);

        $evt->getMessage()->setCc([]);
    }

    /**
     * @param \Swift_Events_SendEvent $evt
     */
    public function sendPerformed(\Swift_Events_SendEvent $evt)
    {
    }
}
