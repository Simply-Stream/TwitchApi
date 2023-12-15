<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\Extension;

class ExtensionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $extension = new Extension(
            'Author',
            true,
            true,
            'hosted',
            'Description',
            'https://www.example.com/eula',
            true,
            'https://www.example.com/icon.png',
            ['24x24' => 'https://www.example.com/icon.png'],
            'id',
            'Extension Name',
            'https://www.example.com/privacy',
            true,
            ['https://www.example.com/screenshot-1.png'],
            'Approved',
            'optional',
            'Summary',
            'example@example.com',
            '1.0.0',
            'Viewer Summary',
            [],
            ['https://www.example.com/config'],
            ['https://www.example.com/panel']
        );

        $this->assertSame('Author', $extension->getAuthorName());
        $this->assertTrue($extension->isBitsEnabled());
        $this->assertTrue($extension->isCanInstall());
        $this->assertSame('hosted', $extension->getConfigurationLocation());
        $this->assertSame('Description', $extension->getDescription());
        $this->assertSame('https://www.example.com/eula', $extension->getEulaTosUrl());
        $this->assertTrue($extension->isHasChatSupport());
        $this->assertSame('https://www.example.com/icon.png', $extension->getIconUrl());
        $this->assertEquals(['24x24' => 'https://www.example.com/icon.png'], $extension->getIconUrls());
        $this->assertSame('id', $extension->getId());
        $this->assertSame('Extension Name', $extension->getName());
        $this->assertSame('https://www.example.com/privacy', $extension->getPrivacyPolicyUrl());
        $this->assertTrue($extension->isRequestIdentityLink());
        $this->assertEquals(['https://www.example.com/screenshot-1.png'], $extension->getScreenshotUrls());
        $this->assertSame('Approved', $extension->getState());
        $this->assertSame('optional', $extension->getSubscriptionsSupportLevel());
        $this->assertSame('Summary', $extension->getSummary());
        $this->assertSame('example@example.com', $extension->getSupportEmail());
        $this->assertSame('1.0.0', $extension->getVersion());
        $this->assertSame('Viewer Summary', $extension->getViewerSummary());
        $this->assertEquals([], $extension->getViews());
        $this->assertEquals(['https://www.example.com/config'], $extension->getAllowlistedConfigUrls());
        $this->assertEquals(['https://www.example.com/panel'], $extension->getAllowlistedPanelUrls());
    }
}
