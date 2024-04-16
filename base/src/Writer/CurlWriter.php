<?php 

namespace App\Writer;

use App\Interface\MessageInterface;
use App\Interface\WriterInterface;
use App\Message\CurlMessage;

class CurlWriter implements WriterInterface
{
    public function writeLog(string|MessageInterface $message): void
    {
        if($message instanceof CurlMessage) $this->sendRequest($message);
    }

    public function sendRequest(CurlMessage $message): void
    {
        if(empty($message->getBody()) || empty($message->getContent())) return;

        $curl = curl_init($message->getUrl());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
        if($message->getMethod() == "POST") {
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,json_decode($message->getBody(),1));
        } else {
            $url = $message->getUrl()."?".http_build_query(json_decode($message->getBody(),1));
            curl_setopt($curl,CURLOPT_URL,$url);
        }

        curl_exec($curl);
    }
}