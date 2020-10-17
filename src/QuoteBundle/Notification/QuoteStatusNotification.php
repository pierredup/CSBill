<?php

declare(strict_types=1);

/*
 * This file is part of SolidInvoice project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SolidInvoice\QuoteBundle\Notification;

use SolidInvoice\NotificationBundle\Notification\NotificationMessage;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class QuoteStatusNotification extends NotificationMessage
{
    const HTML_TEMPLATE = '@SolidInvoiceQuote/Email/status_change.html.twig';

    const TEXT_TEMPLATE = '@SolidInvoiceQuote/Email/status_change.text.twig';

    /**
     * {@inheritdoc}
     */
    public function getHtmlContent(Environment $twig): string
    {
        return $twig->render(self::HTML_TEMPLATE, $this->getParameters());
    }

    /**
     * {@inheritdoc}
     */
    public function getTextContent(Environment $twig): string
    {
        return $twig->render(self::TEXT_TEMPLATE, $this->getParameters());
    }

    public function getSubject(TranslatorInterface $translator): string
    {
        return $translator->trans('quote.status.subject', [], 'email');
    }
}
