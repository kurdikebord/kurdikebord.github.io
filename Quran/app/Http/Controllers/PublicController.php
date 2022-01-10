<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class PublicController extends Controller
{
    public function index()
    {
        $client       = new Client();
        $url          = "https://api.quran.sutanlab.id/surah";

        $response     = $client->request('GET', $url);

        $responseBody = json_decode($response->getBody());

        return view('index', [
            'responseBody' => $responseBody
        ]);
    }

    public function surah(Request $request)
    {
        $client       = new Client();
        $url          = "https://api.quran.sutanlab.id/surah/" . $request->segment(2);

        $response     = $client->request('GET', $url);

        $responseBody = json_decode($response->getBody());

        $surah        = $responseBody->data->name->transliteration->id;

        return view('show', [
            'responseBody' => $responseBody,
            'surah'        => $surah
        ]);
    }
}
