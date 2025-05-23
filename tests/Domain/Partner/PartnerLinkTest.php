<?php

declare(strict_types=1);

namespace Tests\Domain\Partner;

use App\Domain\Partner\PartnerLink;
use PHPUnit\Framework\TestCase;

class PartnerLinkTest extends TestCase
{
    public function testPartnerLinkCreationWithAllFields(): void
    {
        $partnerLink = new PartnerLink(
            1,
            'Example Partner',
            'example-partner',
            'https://example.com',
            'Partner description',
            100
        );

        $this->assertEquals(1, $partnerLink->getId());
        $this->assertEquals('Example Partner', $partnerLink->getName());
        $this->assertEquals('example-partner', $partnerLink->getSlug());
        $this->assertEquals('https://example.com', $partnerLink->getUrl());
        $this->assertEquals('Partner description', $partnerLink->getDescription());
        $this->assertEquals(100, $partnerLink->getClickCount());
    }

    public function testPartnerLinkCreationWithMinimalFields(): void
    {
        $partnerLink = new PartnerLink(
            1,
            'Example Partner',
            'example-partner',
            'https://example.com'
        );

        $this->assertEquals(1, $partnerLink->getId());
        $this->assertEquals('Example Partner', $partnerLink->getName());
        $this->assertEquals('example-partner', $partnerLink->getSlug());
        $this->assertEquals('https://example.com', $partnerLink->getUrl());
        $this->assertEquals('', $partnerLink->getDescription());
        $this->assertEquals(0, $partnerLink->getClickCount());
    }

    public function testIncrementClickCountIncreasesCountByOne(): void
    {
        $partnerLink = new PartnerLink(
            1,
            'Example Partner',
            'example-partner',
            'https://example.com',
            'Description',
            5
        );

        $this->assertEquals(5, $partnerLink->getClickCount());
        
        $partnerLink->incrementClickCount();
        
        $this->assertEquals(6, $partnerLink->getClickCount());
    }

    public function testIncrementClickCountFromZero(): void
    {
        $partnerLink = new PartnerLink(
            1,
            'Example Partner',
            'example-partner',
            'https://example.com'
        );

        $this->assertEquals(0, $partnerLink->getClickCount());
        
        $partnerLink->incrementClickCount();
        
        $this->assertEquals(1, $partnerLink->getClickCount());
    }

    public function testMultipleIncrements(): void
    {
        $partnerLink = new PartnerLink(
            1,
            'Example Partner',
            'example-partner',
            'https://example.com'
        );

        $partnerLink->incrementClickCount();
        $partnerLink->incrementClickCount();
        $partnerLink->incrementClickCount();
        
        $this->assertEquals(3, $partnerLink->getClickCount());
    }

    public function testToArrayContainsAllFields(): void
    {
        $partnerLink = new PartnerLink(
            1,
            'Example Partner',
            'example-partner',
            'https://example.com',
            'Partner description',
            25
        );

        $array = $partnerLink->toArray();

        $expected = [
            'id' => 1,
            'name' => 'Example Partner',
            'slug' => 'example-partner',
            'url' => 'https://example.com',
            'description' => 'Partner description',
            'click_count' => 25
        ];

        $this->assertEquals($expected, $array);
    }

    public function testFromArrayCreatesCorrectPartnerLink(): void
    {
        $data = [
            'id' => 1,
            'name' => 'Example Partner',
            'slug' => 'example-partner',
            'url' => 'https://example.com',
            'description' => 'Partner description',
            'click_count' => 25
        ];

        $partnerLink = PartnerLink::fromArray($data);

        $this->assertEquals(1, $partnerLink->getId());
        $this->assertEquals('Example Partner', $partnerLink->getName());
        $this->assertEquals('example-partner', $partnerLink->getSlug());
        $this->assertEquals('https://example.com', $partnerLink->getUrl());
        $this->assertEquals('Partner description', $partnerLink->getDescription());
        $this->assertEquals(25, $partnerLink->getClickCount());
    }

    public function testFromArrayWithMissingFieldsUsesDefaults(): void
    {
        $data = [
            'id' => 1,
            'name' => 'Example Partner',
            'slug' => 'example-partner',
            'url' => 'https://example.com'
        ];

        $partnerLink = PartnerLink::fromArray($data);

        $this->assertEquals(1, $partnerLink->getId());
        $this->assertEquals('Example Partner', $partnerLink->getName());
        $this->assertEquals('example-partner', $partnerLink->getSlug());
        $this->assertEquals('https://example.com', $partnerLink->getUrl());
        $this->assertEquals('', $partnerLink->getDescription());
        $this->assertEquals(0, $partnerLink->getClickCount());
    }

    public function testArrayConversionRoundtrip(): void
    {
        $originalPartnerLink = new PartnerLink(
            1,
            'Example Partner',
            'example-partner',
            'https://example.com',
            'Partner description',
            25
        );

        $array = $originalPartnerLink->toArray();
        $newPartnerLink = PartnerLink::fromArray($array);

        $this->assertEquals($originalPartnerLink->toArray(), $newPartnerLink->toArray());
    }

    public function testArrayConversionRoundtripAfterIncrement(): void
    {
        $partnerLink = new PartnerLink(
            1,
            'Example Partner',
            'example-partner',
            'https://example.com',
            'Partner description',
            25
        );

        $partnerLink->incrementClickCount();
        
        $array = $partnerLink->toArray();
        $newPartnerLink = PartnerLink::fromArray($array);

        $this->assertEquals(26, $newPartnerLink->getClickCount());
        $this->assertEquals($partnerLink->toArray(), $newPartnerLink->toArray());
    }
}