<?php

namespace ConnySjoblom\Gslt;

use Illuminate\Support\Facades\Http;

class Gslt
{
    /**
     * @param int $appId Steam App Id - https://developer.valvesoftware.com/wiki/Steam_Application_IDs
     * @param string $memo Identifying memo for the GSLT List
     *
     * @return array<string, string> steamid and login_token returned for the new GSLT Key
     *
     * @throws GsltException
     */
    public function get(int $appId, string $memo): array
    {
        $request = Http::asForm()
            ->post('https://api.steampowered.com/IGameServersService/CreateAccount/v1/', [
                "key" => config('gslt.steam.apikey'),
                "appid" => $appId,
                "memo" => $memo,
            ]);

        if ($request->failed()) {
            throw new GsltException("GSLT Request failed.");
        }

        $response = json_decode($request->body())->response;

        return [
            "steamid" => $response->steamid,
            'login_token' => $response->login_token,
        ];
    }

    /**
     * @param string $steamid Steam ID of the GSLT Key
     *
     * @return bool
     */
    public function delete(string $steamid): bool
    {
        $request = Http::asForm()
            ->post('https://api.steampowered.com/IGameServersService/DeleteAccount/v1/', [
                "key" => config('gslt.steam.apikey'),
                "steamid" => $steamid,
            ]);

        if ($request->failed()) {
            return false;
        }

        return true;
    }
}
