<?php

namespace Defr\Tests;

use Defr\Justice;
use Goutte\Client;
use PHPUnit_Framework_TestCase;

final class JusticeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Justice
     */
    private $justice;

    protected function setUp()
    {
        if ($this->isTravis()) {
            $this->markTestSkipped('Travis cannot connect to Justice.cz');
        }

        $this->justice = new Justice(new Client());
    }

    public function testFindById()
    {
        $justiceRecord = $this->justice->findById(27791394);
        $this->assertInstanceOf('Defr\Justice\JusticeRecord', $justiceRecord);

        $people = $justiceRecord->getPeople();
        $this->assertCount(2, $people);

        $this->assertArrayHasKey('Mgr. Robert Runták', $people);
        $person = $people['Mgr. Robert Runták'];
        $this->assertInstanceOf('DateTime', $person->getBirthday());
        $this->assertInternalType('string', $person->getAddress());
    }

    public function testFindByIdWithDiacriticsInName()
    {
        // given
        $expectedName = 'Ing. Petr Šourek';

        // when
        $justiceRecord = $this->justice->findById(41324960);
        $this->assertInstanceOf('Defr\Justice\JusticeRecord', $justiceRecord);

        // then
        $people = $justiceRecord->getPeople();
        $this->assertArrayHasKey($expectedName, $people);
        $person = $people[$expectedName];
        $this->assertEquals($expectedName, $person->getName());
    }

    public function testNotFoundFindId()
    {
        $justiceRecord = $this->justice->findById(123456);
        $this->assertFalse($justiceRecord);
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
