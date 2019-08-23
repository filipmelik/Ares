<?php

namespace Defr\Ares;

use Defr\Justice;
use Defr\ValueObject\Person;
use Goutte\Client as GouteClient;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class AresRecord.
 *
 * @author Dennis Fridrich <fridrich.dennis@gmail.com>
 */
class AresRecord
{
    /**
     * @var string
     */
    private $companyId;

    /**
     * @var string
     */
    private $taxId;

    /**
     * @var string
     */
    private $companyName;

    /**
     * @var string
     */
    private $street;

    /**
     * XML for some companies does not return structured info about about street and its numbers but rather
     * one composite element with street and numbers. That's why this property exists.
     *
     * @var string
     */
    private $fullStreet;

    /**
     * @var string
     */
    private $streetHouseNumber;

    /**
     * @var string
     */
    private $streetOrientationNumber;

    /**
     * @var string
     */
    private $town;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var string
     */
    private $court;

    /**
     * @var string
     */
    private $section;

    /**
     * @var string
     */
    private $subSection;

    /**
     * @var null|GouteClient
     */
    protected $client;

    /**
     * AresRecord constructor.
     *
     * @param string|null $companyId
     * @param string|null $taxId
     * @param string|null $companyName
     * @param string|null $street
     * @param string|null $streetHouseNumber
     * @param string|null $streetOrientationNumber
     * @param string|null $town
     * @param string|null $zip
     * @param string|null $court
     * @param string|null $section
     * @param string|null $subSection
     * @param null        $fullStreet
     */
    public function __construct(
        $companyId = null,
        $taxId = null,
        $companyName = null,
        $street = null,
        $streetHouseNumber = null,
        $streetOrientationNumber = null,
        $town = null,
        $zip = null,
        $court = null,
        $section = null,
        $subSection = null,
        $fullStreet = null
    ) {
        $this->companyId = $companyId;
        $this->taxId = !empty($taxId) ? $taxId : null;
        $this->companyName = $companyName;
        $this->street = $street;
        $this->streetHouseNumber = !empty($streetHouseNumber) ? $streetHouseNumber : null;
        $this->streetOrientationNumber = !empty($streetOrientationNumber) ? $streetOrientationNumber : null;
        $this->town = $town;
        $this->zip = $zip;
        $this->court = $court;
        $this->section = $section;
        $this->subSection = $subSection;
        $this->fullStreet = $fullStreet;
    }

    /**
     * @return string
     */
    public function getStreetWithNumbers()
    {
        if (!empty($this->fullStreet)) {
            return $this->fullStreet;
        } else {
            return $this->street.' '
                .($this->streetOrientationNumber
                    ?
                    $this->streetHouseNumber.'/'.$this->streetOrientationNumber
                    :
                    $this->streetHouseNumber);
        }
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->companyName;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @return mixed
     */
    public function getTaxId()
    {
        return $this->taxId;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return mixed
     */
    public function getStreetHouseNumber()
    {
        return $this->streetHouseNumber;
    }

    /**
     * @return mixed
     */
    public function getStreetOrientationNumber()
    {
        return $this->streetOrientationNumber;
    }

    /**
     * @return mixed
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @return null|string
     */
    public function getCourt()
    {
        return $this->court;
    }

    /**
     * @return null|string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @return null|string
     */
    public function getSubSection()
    {
        return $this->subSection;
    }

    /**
     * @return null|string
     */
    public function getSectionWithSubSection()
    {
        if ($this->section === null || $this->subSection === null) {
            return null;
        }

        return sprintf('%s %s', $this->section, $this->subSection);
    }

    /**
     * @param GouteClient $client
     *
     * @return $this
     */
    public function setClient(GouteClient $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return GouteClient
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new GouteClient();
            $this->client->setClient(new GuzzleClient(['verify' => false]));
        }

        return $this->client;
    }

    /**
     * @return array|Person[]
     */
    public function getCompanyPeople()
    {
        $client = $this->getClient();
        $justice = new Justice($client);
        $justiceRecord = $justice->findById($this->companyId);
        if ($justiceRecord) {
            return $justiceRecord->getPeople();
        }

        return [];
    }

    /**
     * @param int $companyId
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * @param string $taxId
     */
    public function setTaxId($taxId)
    {
        $this->taxId = $taxId;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @param string $streetHouseNumber
     */
    public function setStreetHouseNumber($streetHouseNumber)
    {
        $this->streetHouseNumber = $streetHouseNumber;
    }

    /**
     * @param string $streetOrientationNumber
     */
    public function setStreetOrientationNumber($streetOrientationNumber)
    {
        $this->streetOrientationNumber = $streetOrientationNumber;
    }

    /**
     * @param string $town
     */
    public function setTown($town)
    {
        $this->town = $town;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @param string $managingCourt
     */
    public function setCourt($managingCourt)
    {
        $this->court = $managingCourt;
    }

    /**
     * @param string $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @param string $subSection
     */
    public function setSubSection($subSection)
    {
        $this->subSection = $subSection;
    }

    /**
     * @param string $fullStreet
     */
    public function setFullStreet($fullStreet)
    {
        $this->fullStreet = $fullStreet;
    }

}
