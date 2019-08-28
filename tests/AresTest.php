<?php

namespace Defr\Ares\Tests;

use Defr\Ares;
use PHPUnit_Framework_TestCase;

final class AresTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ares
     */
    private $ares;

    protected function setUp()
    {
        $this->ares = new Ares();
    }

    /**
     * @param $companyId
     * @param $expectedException
     * @param $expectedExceptionMessage
     * @param $expectedAresRecord
     * @param $expectedFullTown
     *
     * @throws Ares\AresException
     * @dataProvider providerTestFindByIdentificationNumber
     *
     */
    public function testFindByIdentificationNumber(
        $companyId,
        $expectedException,
        $expectedExceptionMessage,
        $expectedAresRecord,
        $expectedFullTown
    )
    {
        // setup
        if ($expectedException !== null) {
            $this->expectException($expectedException);
        }
        if ($expectedExceptionMessage !== null) {
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        // when
        $actualAresRecord = $this->ares->findByIdentificationNumber($companyId);
        $actualFullTown = $actualAresRecord->getFullTown();

        // then
        $this->assertEquals($expectedAresRecord, $actualAresRecord);
        $this->assertEquals($expectedFullTown, $actualFullTown);
    }

    /**
     * @return array
     */
    public function providerTestFindByIdentificationNumber()
    {
        return [
            [
                // integer ID number
                'companyId'                => 48136450,
                'expectedException'        => null,
                'expectedExceptionMessage' => null,
                'expectedAresRecord'       => new Ares\AresRecord(
                    '48136450',
                    'CZ48136450',
                    'ČESKÁ NÁRODNÍ BANKA',
                    'Na příkopě',
                    '864',
                    '28',
                    'Praha',
                    'Praha 1',
                    'Nové Město',
                    '11000'
                ),
                'expectedFullTown' => 'Praha - Nové Město',
            ],
            [
                // string ID number
                'companyId'                => '48136450',
                'expectedException'        => null,
                'expectedExceptionMessage' => null,
                'expectedAresRecord'       => new Ares\AresRecord(
                    '48136450',
                    'CZ48136450',
                    'ČESKÁ NÁRODNÍ BANKA',
                    'Na příkopě',
                    '864',
                    '28',
                    'Praha',
                    'Praha 1',
                    'Nové Město',
                    '11000'
                ),
                'expectedFullTown' => 'Praha - Nové Město',
            ],
            [
                // string ID number with leading zeros
                'companyId'                => '00006947',
                'expectedException'        => null,
                'expectedExceptionMessage' => null,
                'expectedAresRecord'       => new Ares\AresRecord(
                    '00006947',
                    'CZ00006947',
                    'Ministerstvo financí',
                    'Letenská',
                    '525',
                    '15',
                    'Praha',
                    'Praha 1',
                    'Malá Strana',
                    '11800'
                ),
                'expectedFullTown' => 'Praha - Malá Strana',
            ],
            [
                // nonsense string ID number with some charaters in it
                'companyId'                => 'ABC1234',
                'expectedException'        => \InvalidArgumentException::class,
                'expectedExceptionMessage' => 'IČ firmy musí být číslo.',
                'expectedAresRecord'       => null,
                'expectedFullTown'         => null,
            ],
            [
                // empty string ID number
                'companyId'                => '',
                'expectedException'        => \InvalidArgumentException::class,
                'expectedExceptionMessage' => 'IČ firmy musí být číslo.',
                'expectedAresRecord'       => null,
                'expectedFullTown'         => null,
            ],
            [
                // non-existent ID number
                'companyId'                => '12345678912345',
                'expectedException'        => \Defr\Ares\AresException::class,
                'expectedExceptionMessage' => 'IČ firmy nebylo nalezeno.',
                'expectedAresRecord'       => null,
                'expectedFullTown'         => null,
            ],
            [
                // string ID number with leading zeros
                'companyId'                => '04084063',
                'expectedException'        => null,
                'expectedExceptionMessage' => null,
                'expectedAresRecord'       => new Ares\AresRecord(
                    '04084063',
                    'CZ04084063',
                    'Česká telekomunikační infrastruktura a.s.',
                    'Olšanská',
                    '2681',
                    '6',
                    'Praha',
                    'Praha 3',
                    'Žižkov',
                    '13000',
                    'Městský soud v Praze',
                    'B',
                    '20623'
                ),
                'expectedFullTown' => 'Praha - Žižkov',
            ],
            [
                // company with address that is returned in composite element AA-CA and has weird VAT ID
                'companyId'                => '60192852',
                'expectedException'        => null,
                'expectedExceptionMessage' => null,
                'expectedAresRecord'       => new Ares\AresRecord(
                    '60192852',
                    'Skupinove_DPH',
                    'Modrá pyramida stavební spořitelna, a.s.',
                    'Bělehradská',
                    null,
                    null,
                    'Praha 2',
                    '',
                    '',
                    '12021',
                    'Městský soud v Praze',
                    'B',
                    '2281',
                    'Bělehradská 128, čp.222'
                ),
                'expectedFullTown' => 'Praha 2',
            ],
            [
                // company with town that is returned in composite element AA-CA
                'companyId'                => '05391423',
                'expectedException'        => null,
                'expectedExceptionMessage' => null,
                'expectedAresRecord'       => new Ares\AresRecord(
                    '05391423',
                    'CZ05391423',
                    'Seco Industries, s.r.o.',
                    'Podnikatelská',
                    '552',
                    null,
                    'Praha',
                    'Praha-Běchovice',
                    'Běchovice',
                    '19011',
                    'Městský soud v Praze',
                    'C',
                    '262957',
                    null
                ),
                'expectedFullTown' => 'Praha - Běchovice',
            ],
        ];
    }

    public function testFindByName()
    {
        $results = $this->ares->findByName('sever');

        $this->assertGreaterThan(0, count($results));
    }

    /**
     * @expectedException \Defr\Ares\AresException
     * @expectedExceptionMessage Nic nebylo nalezeno.
     */
    public function testFindByNameNonExistentName()
    {
        $this->ares->findByName('some non-existent company name');
    }

    public function testGetCompanyPeople()
    {
        if ($this->isTravis()) {
            $this->markTestSkipped('Travis cannot connect to Justice.cz');
        }

        $record = $this->ares->findByIdentificationNumber(27791394);
        $companyPeople = $record->getCompanyPeople();
        $this->assertCount(2, $companyPeople);
    }

    public function testBalancer()
    {
        $this->markTestSkipped('not functioning: FIXME');
        $ares = new Ares();
        $ares->setBalancer('http://some.loadbalancer.domain');
        try {
            $ares->findByIdentificationNumber(26168685);
        } catch (Ares\AresException $e) {
            throw $e;
        }
        $this->assertEquals(
            'http://some.loadbalancer.domain'
            .'?url=http%3A%2F%2Fwwwinfo.mfcr.cz%2Fcgi-bin%2Fares%2Fdarv_bas.cgi%3Fico%3D26168685',
            $ares->getLastUrl()
        );
    }

    /**
     * @return bool
     */
    private function isTravis()
    {
        if (getenv('TRAVIS_PHP_VERSION')) {
            return true;
        }

        return false;
    }
}
