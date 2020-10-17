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

namespace SolidInvoice\ApiBundle\Serializer\Normalizer;

use Doctrine\Common\Persistence\ManagerRegistry;
use SolidInvoice\ClientBundle\Entity\AdditionalContactDetail;
use SolidInvoice\ClientBundle\Entity\ContactType;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AdditionalContactDetailsNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @var NormalizerInterface|DenormalizerInterface
     */
    private $normalizer;

    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry, NormalizerInterface $normalizer)
    {
        if (!$normalizer instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException('The normalizer must implement '.DenormalizerInterface::class);
        }

        $this->normalizer = $normalizer;
        $this->registry = $registry;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $data['type'] = [
            'name' => $data['type'],
        ];

        /* @var AdditionalContactDetail $detail */
        $detail = $this->normalizer->denormalize($data, $class, $format, $context);
        $repository = $this->registry->getRepository(ContactType::class);
        $detail->setType($repository->findOneBy(['name' => $detail->getType()->getName()]));

        return $detail;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return AdditionalContactDetail::class === $type;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        /* @var AdditionalContactDetail $object */
        return ['type' => $object->getType()->getName(), 'value' => $object->getValue()];
    }

    public function supportsNormalization($data, $format = null)
    {
        return is_object($data) && AdditionalContactDetail::class === get_class($data);
    }
}
