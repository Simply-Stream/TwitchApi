<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Bits;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Bits\Cheermote;
use SimplyStream\TwitchApi\Helix\Models\Bits\Tier;

final class CheermoteTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $prefix = ' example_prefix';
        $tier1 = new Tier(1, 'color', 'ID', [], true, false);
        $tiers = [
            $tier1,
        ];
        $type = 'global_first_party';
        $order = 1;
        $lastUpdated = new DateTimeImmutable();
        $isCharitable = false;

        $cheermote = new Cheermote($prefix, $tiers, $type, $order, $lastUpdated, $isCharitable);

        $this->assertEquals($prefix, $cheermote->getPrefix());
        $this->assertEquals($tiers, $cheermote->getTiers());
        $this->assertEquals($type, $cheermote->getType());
        $this->assertEquals($order, $cheermote->getOrder());
        $this->assertEquals($lastUpdated, $cheermote->getLastUpdated());
        $this->assertEquals($isCharitable, $cheermote->isCharitable());

        $this->assertIsArray($cheermote->toArray());
        $this->assertSame([
            'prefix' => $prefix,
            'tiers' => [
                [
                    'min_bits' => $tier1->getMinBits(),
                    'id' => $tier1->getId(),
                    'color' => $tier1->getColor(),
                    'images' => $tier1->getImages(),
                    'can_cheer' => $tier1->isCanCheer(),
                    'show_in_bits_card' => $tier1->isShowInBitsCard(),
                ],
            ],
            'type' => $type,
            'order' => $order,
            'last_updated' => $lastUpdated->format(DATE_RFC3339_EXTENDED),
            'is_charitable' => $isCharitable,
        ], $cheermote->toArray());
    }
}
