<?php
/**
 * User: Oscar Sanchez
 * Date: 27/4/20
 */

namespace OsNinjaFormSync\Util;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class FileLogger
{
    /***
     * @var Logger
     */
    private $logger;

    public function __construct(string $name ,string $path)
    {
        $this->logger = new Logger($name);
        $this->logger->pushHandler(new StreamHandler($path,LOGGER::DEBUG));
    }

    /**
     * @param string $text
     */
    public function addInfo(string $text, array $context = [])
    {
        $this->logger->info($text, $context);
    }

    /**
     * @param string $text
     */
    public function addError(string $text)
    {
        $this->logger->error($text);
    }

}