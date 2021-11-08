<?php
 
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
 
class CallApiService
{
    private $client;
    private $rapidApiKey;
 
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->rapidApiKey = 'db0435b266mshf21bc3c9584e6afp1f4b36jsn883554194bda';
    }
 
    public function getRewriterData(string $text)
    {
        $response = $this->client->request('POST', 'https://rewriter-paraphraser-text-changer-multi-language.p.rapidapi.com/rewrite', [
            'headers' => [
                'content-type' => 'application/json',
                'x-rapidapi-host' => 'rewriter-paraphraser-text-changer-multi-language.p.rapidapi.com',
                'x-rapidapi-key' => $this->rapidApiKey
            ],
            'json' => [
                "language" => "fr",
                "strength"=> 3,
                "text" => $text
            ],
        ]);
 
        return $response->toArray() ;
    }
}