<?php

namespace App\Logger\Writer;

use App\Logger\Interface\Configurated;
use App\Logger\Interface\WriterInterface;

class FileWriter implements WriterInterface, Configurated
{

    private string $filename;

    public function __construct(string|null $filename = null)
    {
        $this->filename = $filename ?? $this->getConfig()['filename'];
    }

    public function writeLog(string $message): void
    {
        if(empty($message)) return;

        $this->write($message);
    }

    public function getConfig(): ?array
    {
        return ["config" => self::QCONFIG, "filename" => self::FILE_NAME];
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