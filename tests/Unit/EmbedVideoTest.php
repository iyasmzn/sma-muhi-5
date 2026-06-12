<?php

namespace Tests\Unit;

use App\Services\EmbedVideo;
use PHPUnit\Framework\TestCase;

class EmbedVideoTest extends TestCase
{
    public function test_detects_youtube_provider(): void
    {
        $this->assertSame('youtube', EmbedVideo::detectProvider('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertSame('youtube', EmbedVideo::detectProvider('https://youtu.be/dQw4w9WgXcQ'));
    }

    public function test_detects_tiktok_and_instagram_providers(): void
    {
        $this->assertSame('tiktok', EmbedVideo::detectProvider('https://www.tiktok.com/@user/video/7012345678901234567'));
        $this->assertSame('instagram', EmbedVideo::detectProvider('https://www.instagram.com/reel/CabcdefghIj/'));
    }

    public function test_returns_null_for_unsupported_url(): void
    {
        $this->assertNull(EmbedVideo::detectProvider('https://example.com/video/123'));
        $this->assertNull(EmbedVideo::detectProvider('not-a-url'));
    }

    public function test_extracts_youtube_id_from_all_url_shapes(): void
    {
        $urls = [
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'https://youtu.be/dQw4w9WgXcQ',
            'https://www.youtube.com/shorts/dQw4w9WgXcQ',
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'https://www.youtube.com/watch?list=ABC&v=dQw4w9WgXcQ',
        ];

        foreach ($urls as $url) {
            $this->assertSame('dQw4w9WgXcQ', EmbedVideo::extractId('youtube', $url), $url);
        }
    }

    public function test_extracts_tiktok_id_and_instagram_shortcode(): void
    {
        $this->assertSame('7012345678901234567', EmbedVideo::extractId('tiktok', 'https://www.tiktok.com/@scout/video/7012345678901234567'));
        $this->assertSame('CabcdefghIj', EmbedVideo::extractId('instagram', 'https://www.instagram.com/reel/CabcdefghIj/'));
    }

    public function test_builds_embed_src_per_provider(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            EmbedVideo::embedSrc('youtube', 'https://youtu.be/dQw4w9WgXcQ')
        );
        $this->assertSame(
            'https://www.tiktok.com/embed/v2/7012345678901234567',
            EmbedVideo::embedSrc('tiktok', 'https://www.tiktok.com/@scout/video/7012345678901234567')
        );
        $this->assertSame(
            'https://www.instagram.com/reel/CabcdefghIj/embed',
            EmbedVideo::embedSrc('instagram', 'https://www.instagram.com/reel/CabcdefghIj/?igsh=abc')
        );
    }

    public function test_youtube_thumbnail_uses_real_image_others_use_badge(): void
    {
        $this->assertSame(
            'https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg',
            EmbedVideo::thumbnail('youtube', 'https://youtu.be/dQw4w9WgXcQ')
        );

        $this->assertStringStartsWith('data:image/svg+xml,', EmbedVideo::thumbnail('tiktok', 'https://www.tiktok.com/@scout/video/7012345678901234567'));
        $this->assertStringStartsWith('data:image/svg+xml,', EmbedVideo::thumbnail('instagram', 'https://www.instagram.com/reel/CabcdefghIj/'));
    }

    public function test_is_valid_rejects_provider_pages_without_video_id(): void
    {
        $this->assertTrue(EmbedVideo::isValid('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertFalse(EmbedVideo::isValid('https://www.youtube.com/@somechannel'));
        $this->assertFalse(EmbedVideo::isValid('https://example.com/clip'));
    }

    public function test_iframe_html_returns_null_when_unparseable(): void
    {
        $this->assertNull(EmbedVideo::iframeHtml('youtube', 'https://www.youtube.com/@somechannel'));
        $this->assertStringContainsString('<iframe', (string) EmbedVideo::iframeHtml('youtube', 'https://youtu.be/dQw4w9WgXcQ'));
    }
}
