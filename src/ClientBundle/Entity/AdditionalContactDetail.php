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

namespace SolidInvoice\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SolidInvoice\CoreBundle\Traits\Entity;
use Symfony\Component\Serializer\Annotation as Serialize;

/**
 * SolidInvoice\ClientBundle\Entity\AdditionalContactDetail.
 *
 * @ORM\Entity()
 * @ORM\Table(name="contact_details")
 * @Gedmo\Loggable
 */
class AdditionalContactDetail
{
    use Entity\TimeStampable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serialize\Groups({"client_api", "contact_api"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=false)
     * @Serialize\Groups({"client_api", "contact_api"})
     */
    protected $value;

    /**
     * @var ContactType
     *
     * @ORM\ManyToOne(targetEntity="ContactType", inversedBy="details")
     * @ORM\JoinColumn(name="contact_type_id", referencedColumnName="id")
     * @Serialize\Groups({"client_api", "contact_api"})
     */
    protected $type;

    /**
     * @var Contact
     *
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="additionalContactDetails")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     * @Serialize\Groups({"js"})
     */
    private $contact;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return AdditionalContactDetail
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get type.
     *
     * @return ContactType
     */
    public function getType(): ?ContactType
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param ContactType $type
     *
     * @return AdditionalContactDetail
     */
    public function setType(?ContactType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    /**
     * Get contact.
     *
     * @return Contact
     */
    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    /**
     * Set contact.
     *
     * @return AdditionalContactDetail
     */
    public function setContact(Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }
}
