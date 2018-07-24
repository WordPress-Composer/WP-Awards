<?php 

namespace Voting\Helper;

use RicardoFiorani\Matcher\VideoServiceMatcher;
use RicardoFiorani\Exception\ServiceNotAvailableException;
use RicardoFiorani\Exception\ServiceApiNotAvailable;
use Exception;

/**
 * Video service that parses video urls into 
 * ids
 * 
 * @author Gemma Black <gblackuk@gmail.com>
 */
class VideoService
{
    private $log;
    private $url;
    private $video;

    /**
     * @param Log $log
     * @param string $url
     */
    public function __construct(Log $log, $url)
    {
        $this->log = $log;
        $this->url = $url;

        if ($this->isURL($url)) {
            try {
                $parser = new VideoServiceMatcher();
                $this->video = $parser->parse($this->url);
            } 
            catch (ServiceNotAvailableException $e) {
                $log->error($e);
            }  
            catch (Exception $e) {
                $log->error($e);
            }
        }
    }

    /**
     * Rather than break the API output, no videoId is 
     * outputted, and error logged
     *
     * @return null|string
     */
    public function getId()
    {
        if (is_null($this->video)) {
            return null;
        }

        if (empty($this->url)) {
            return null;
        }

        try {
            return $this->video->getVideoId();
        }
            
        catch (Exception $e) {
            $this->log->error($e);
            return null;
        }
    }

    /**
     * Rather than break the API output, no type is 
     * outputted, and error logged
     *
     * @return null|string
     */
    public function getType()
    {
        if (is_null($this->video)) {
            return null;
        }

        if (empty($this->url)) {
            return null;
        }

        try {
            return strtolower($this->video->getServiceName());
        }
        catch (Exception $e) {
            $this->log->error($e);
            return null;
        }
    }

    /**
     * Is var a url check
     *
     * @param string $url
     * @return boolean
     */
    private function isUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}