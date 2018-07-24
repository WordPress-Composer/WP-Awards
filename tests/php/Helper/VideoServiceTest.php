<?php

use PHPUnit\Framework\TestCase;
use Voting\Helper\VideoService;
use Voting\Helper\Log;

class VideoServiceTest extends TestCase {

    protected $log;

    protected function setUp()
    {
        $this->log = $this->createMock(Log::class);
        $this->log->method('error')
            ->willReturn('');
    }

    public function test_service_only_accepts_valid_urls()
    {
        $url = 'vimeo.com/api/v2/video/261674587';
        $service = new VideoService($this->log, $url);
        $response = $service->getId();
        $this->assertEquals(null, $response);
    }

    public function test_service_only_accepts_non_file_urls()
    {
        $url = 'http://vimeo.com/api/v2/video/261674587.php';
        $service = new VideoService($this->log, $url);
        $response = $service->getId();
        $this->assertEquals(null, $response);
    }

    public function test_youtube_url_returns_id()
    {
        $url = 'https://www.youtube.com/watch?v=k7n2xnOiWI8';
        $service = new VideoService($this->log, $url);
        $response = $service->getId();
        $this->assertEquals('k7n2xnOiWI8', $response);
    }

    public function test_vimeo_url_returns_id()
    {
        $url = 'https://vimeo.com/channels/staffpicks/261414977';
        $service = new VideoService($this->log, $url);
        $response = $service->getId();
        $this->assertEquals('261414977', $response);
    }

    public function test_vimeo_url_returns_id_2()
    {
        $url = 'https://vimeo.com/66352586';
        $service = new VideoService($this->log, $url);
        $response = $service->getId();
        $this->assertEquals('66352586', $response);
    }

    public function test_video_type_returns_youtube_with_youtube_url()
    {
        $url = 'https://www.youtube.com/watch?v=k7n2xnOiWI8';
        $service = new VideoService($this->log, $url);
        $response = $service->getType();
        $this->assertEquals('youtube', $response);
    }

    public function test_video_type_returns_vimeo_with_vimeo_url()
    {
        $url = 'https://vimeo.com/channels/staffpicks/261414977';
        $service = new VideoService($this->log, $url);
        $response = $service->getType();
        $this->assertEquals('vimeo', $response);
    }
}