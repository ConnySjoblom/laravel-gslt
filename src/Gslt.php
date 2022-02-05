<?php

namespace ConnySjoblom\Gslt;

use Illuminate\Support\Facades\Http;

class Gslt
{
    /**
     * @param int $appId
     * @param string $name
     *
     * @return array
     *
     * @throws GsltException
     */
    public function get(int $appId, string $name): array
    {
        $request = Http::asForm()
            ->post('https://api.steampowered.com/IGameServersService/CreateAccount/v1/', [
                "key" => config('gslt.steam.apikey'),
                "appid" => $appId,
                "memo" => $name
            ]);

        if ($request->failed()) {
            throw new GsltException("GSLT Request failed.");
        }

        $response = json_decode($request->body())->response;

        return [
            "steamid" => $response->steamid,
            'login_token' => $response->login_token
        ];
    }

    /**
     * @param string $steamid
     *
     * @return bool
     */
    public function delete(string $steamid): bool
    {
        $request = Http::asForm()
            ->post('https://api.steampowered.com/IGameServersService/DeleteAccount/v1/', [
                "key" => config('gslt.steam.apikey'),
                "steamid" => $steamid
            ]);

        if ($request->failed()) {
            return false;
        }

        return true;
    }
}
