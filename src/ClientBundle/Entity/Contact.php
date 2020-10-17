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

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SolidInvoice\CoreBundle\Traits\Entity;
use SolidInvoice\InvoiceBundle\Entity\Invoice;
use SolidInvoice\QuoteBundle\Entity\Quote;
use Symfony\Component\Serializer\Annotation as Serialize;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(attributes={"normalization_context"={"groups"={"contact_api"}}, "denormalization_context"={"groups"={"contact_api"}}}, collectionOperations={"post"={"method"="POST"}}, iri="https://schema.org/Person")
 * @ORM\Table(name="contacts", indexes={@ORM\Index(columns={"email"})})
 * @ORM\Entity(repositoryClass="SolidInvoice\ClientBundle\Repository\ContactRepository")
 * @Gedmo\Loggable
 */
class Contact implements \Serializable
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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=125, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=125)
     * @Serialize\Groups({"client_api", "contact_api"})
     * @ApiProperty(iri="https://schema.org/givenName")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=125, nullable=true)
     * @Assert\Length(max=125)
     * @Serialize\Groups({"client_api", "contact_api"})
     * @ApiProperty(iri="https://schema.org/familyName")
     */
    private $lastName;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="contacts")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * @Serialize\Groups({"contact_api"})
     * @ApiProperty(iri="https://schema.org/Organization")
     * @Assert\Valid()
     * @Assert\NotBlank()
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email(mode="strict")
     * @Serialize\Groups({"client_api", "contact_api"})
     * @ApiProperty(iri="https://schema.org/email")
     */
    private $email;

    /**
     * @var Collection|AdditionalContactDetail[]
     *
     * @ORM\OneToMany(targetEntity="AdditionalContactDetail", mappedBy="contact", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     * @Serialize\Groups({"client_api", "contact_api"})
     */
    private $additionalContactDetails;

    /**
     * @var Collection|Invoice[]
     *
     * @ORM\ManyToMany(targetEntity="SolidInvoice\InvoiceBundle\Entity\Invoice", cascade={"persist"}, fetch="EXTRA_LAZY", mappedBy="users")
     */
    private $invoices;

    /**
     * @var Collection|Quote[]
     *
     * @ORM\ManyToMany(targetEntity="SolidInvoice\QuoteBundle\Entity\Quote", cascade={"persist"}, fetch="EXTRA_LAZY", mappedBy="users")
     */
    private $quotes;

    public function __construct()
    {
        $this->additionalContactDetails = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->quotes = new ArrayCollection();
    }

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
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set firstName.
     *
     * @return Contact
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set lastname.
     *
     * @param string $lastName
     *
     * @return Contact
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get client.
     *
     * @return Client
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * Set client.
     *
     * @return Contact
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Add additional detail.
     *
     * @return Contact
     */
    public function addAdditionalContactDetail(AdditionalContactDetail $detail): self
    {
        $this->additionalContactDetails->add($detail);
        $detail->setContact($this);

        return $this;
    }

    /**
     * Removes additional detail from the current contact.
     *
     * @return Contact
     */
    public function removeAdditionalContactDetail(AdditionalContactDetail $detail): self
    {
        $this->additionalContactDetails->removeElement($detail);

        return $this;
    }

    /**
     * Get additional details.
     *
     * @return Collection|AdditionalContactDetail[]
     */
    public function getAdditionalContactDetails(): Collection
    {
        return $this->additionalContactDetails;
    }

    public function getAdditionalContactDetail(string $type): ?AdditionalContactDetail
    {
        $type = strtolower($type);
        if (count($this->additionalContactDetails)) {
            foreach ($this->additionalContactDetails as $detail) {
                if (strtolower((string) $detail->getType()) === $type) {
                    return $detail;
                }
            }
        }

        return null;
    }

    public function serialize(): string
    {
        return serialize([$this->id, $this->firstName, $this->lastName, $this->created, $this->updated]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($this->id, $this->firstName, $this->lastName, $this->created, $this->updated) = unserialize($serialized);
    }

    public function __toString(): string
    {
        return $this->firstName.' '.$this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return Contact
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    /**
     * @return Collection|Quote[]
     */
    public function getQuotes(): Collection
    {
        return $this->quotes;
    }
}
