<?php

namespace App\Logger\Writer;

use App\Logger\Interface\WriterInterface;

class FileWriter implements WriterInterface
{

    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function writeLog(string $message): void
    {
        if(empty($message)) return;

        $this->write($message);
    }

    protected function write(string $text): void
    {
        $ofstream = fopen($this->filename,"a+");
        if($this->validateFile()){
            fwrite($ofstream,$text);
            fclose($ofstream);
        }
    }

    protected function validateFile(): bool
    {
        if(!file_exists($this->filename) || !is_writable($this->filename)) return false;
        return true;
    }
}