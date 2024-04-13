<?php

namespace App\Writer;

use App\Interface\Configurable;
use App\Interface\MessageInterface;
use App\Interface\WriterInterface;
use App\Utils\ConfigProvider;

class FileWriter implements WriterInterface, Configurable
{

    private string $filename;

    public function __construct(string|null $filename = null)
    {
        $this->filename = $filename ?? $this->getConfig()['log_filename'];
    }

    public function writeLog(string|MessageInterface $message): void
    {
        if(empty($message)) return;

        $this->write($message);
    }

    public function getConfig(): ?array
    {
        return ConfigProvider::getConfigVariable("baseLogger");
    }

    private function write(string $text): void
    {
        $ofstream = fopen($this->filename,"a+");
        if($this->validateFile()){
            fwrite($ofstream,$text);
            fwrite($ofstream,PHP_EOL);
            fclose($ofstream);
        }
    }

    private function validateFile(): bool
    {
        if(!file_exists($this->filename) || !is_writable($this->filename)) return false;
        return true;
    }
}