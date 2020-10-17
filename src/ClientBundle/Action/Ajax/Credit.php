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

namespace SolidInvoice\ClientBundle\Action\Ajax;

use Money\Currency;
use Money\Money;
use SolidInvoice\ClientBundle\Entity\Client;
use SolidInvoice\ClientBundle\Repository\CreditRepository;
use SolidInvoice\CoreBundle\Response\AjaxResponse;
use SolidInvoice\CoreBundle\Traits\JsonTrait;
use SolidInvoice\MoneyBundle\Formatter\MoneyFormatter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class Credit implements AjaxResponse
{
    use JsonTrait;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var CreditRepository
     */
    private $repository;

    /**
     * @var MoneyFormatter
     */
    private $formatter;

    public function __construct(CreditRepository $repository, MoneyFormatter $formatter, Currency $currency)
    {
        $this->currency = $currency;
        $this->repository = $repository;
        $this->formatter = $formatter;
    }

    public function get(Client $client): JsonResponse
    {
        return $this->toJson($client);
    }

    public function put(Request $request, Client $client): JsonResponse
    {
        $value = new Money((int) ((json_decode($request->getContent() ?? '[]', true)['credit'] ?? 0) * 100), $client->getCurrency() ?? $this->currency);

        $this->repository->addCredit($client, $value);

        return $this->toJson($client);
    }

    private function toJson(Client $client)
    {
        return $this->json(
            [
                'credit' => $this->formatter->toFloat($client->getCredit()->getValue()),
                'id' => $client->getId(),
            ]
        );
    }
}
