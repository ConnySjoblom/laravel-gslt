<?php

namespace ConnySjoblom\Gslt;

use Illuminate\Support\Facades\Http;

class Gslt
{
    /**
     * @param int    $appId Steam App Id - https://developer.valvesoftware.com/wiki/Steam_Application_IDs
     * @param string $memo  Identifying memo for the GSLT List
     *
     * @return array<string, string>|null steamid and login_token returned for the new GSLT Key
     */
    public function create(int $appId, string $memo): ?array
    {
        $request = Http::asForm()
            ->post('https://api.steampowered.com/IGameServersService/CreateAccount/v1/', [
                "key" => config('gslt.steam.apikey'),
                "appid" => $appId,
                "memo" => $memo,
            ]);

        if ($request->failed()) {
            return null;
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
     * @return bool Status of deletion
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

    /**
     * @param string $steamid Steam ID of the GSLT Key
     *
     * @return bool Status of reset
     */
    public function reset(string $steamid): bool
    {
        $request = Http::asForm()
            ->post('https://api.steampowered.com/IGameServersService/ResetLoginToken/v1/', [
                "key" => config('gslt.steam.apikey'),
                "steamid" => $steamid,
            ]);

        if ($request->failed()) {
            return false;
        }

        return true;
    }

    /**
     * @param string $login_token GSLT Key
     *
     * @return array<string, mixed>|null is_banned, expires and steamid returned from status check
     */
    public function status(string $login_token): ?array
    {
        $request = Http::asForm()
            ->get('https://api.steampowered.com/IGameServersService/QueryLoginToken/v1/', [
                "key" => config('gslt.steam.apikey'),
                "login_token" => $login_token
            ]);

        $response = json_decode($request->body())->response;

        if ($response->failed()) {
            return null;
        }

        return [
            'is_banned' => $response->is_banned,
            'expires' => $response->expires,
            'steamid' => $response->steamid
        ];
    }
}
