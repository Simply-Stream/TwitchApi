<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\ChannelPoints;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\RedemptionStatusRequest;

final class RedemptionStatusRequestTest extends TestCase
{
    #[DataProvider('provideValidStatus')]
    public function testConstructWithValidStatus(string $status)
    {
        $reddemStatusRequest = new RedemptionStatusRequest($status);

        $this->assertSame($status, $reddemStatusRequest->getStatus());

        $this->assertIsArray($reddemStatusRequest->toArray());
        $this->assertSame([
            'status' => $status,
        ], $reddemStatusRequest->toArray());
    }

    public function testConstructWithInvalidStatus(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected status to be one of: "CANCELED", "FULFILLED". Got: "INVALID_STATUS"');

        new RedemptionStatusRequest('INVALID_STATUS');
    }

    public function provideValidStatus(): \Generator
    {
        yield 'canceled status' => ['CANCELED'];
        yield 'fulfilled status' => ['FULFILLED'];
    }
}
