<?php

namespace App\Http\Controllers;

use SimpleXMLElement;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Cookie;

class SteamController extends Controller
{
    const STEAM_LOGIN = 'https://steamcommunity.com/openid/login';

    private $steamID;

    public function cookie()
    {
        return Cookie::get('steamID64', 'not found');
    }

    public function index()
    {
        $url = $this->getAuthUrl(url('/steam'));
        return view('login.index', compact('url'));
    }

    public function processUser()
    {
        $steamID = $this->validateSteam();
        $this->steamID = $steamID;

        // Return the user to an error page if Steam ID is empty
        if (empty($steamID)) return view('login.error');

        $user = $this->getUserInfo();

        User::updateOrCreate(['steam_id' => $steamID], [
            'steam_id' => $steamID,
            'name' => $user["steamID"],
            'avatar' => $user["avatarFull"]
        ]);

        // $cookie = Cookie::forever('steamID64', $steamID);
        Cookie::queue('steamID64', $steamID, 300);

        return $user;
    }

    public function getUserInfo()
    {
        $xmlstring = file_get_contents("http://steamcommunity.com/profiles/" . $this->steamID . "/?xml=1");
        $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, true);
        return $array;
    }

    /**
     * Get the URL to sign into steam
     *
     * @param mixed returnTo URI to tell steam where to return, MUST BE THE FULL URI WITH THE PROTOCOL
     * @param bool useAmp Use &amp; in the URL, true; or just &, false.
     * @return string The string to go in the URL
     */
    public function getAuthUrl($returnTo = false, $useAmp = true)
    {
        $returnTo = (!$returnTo) ? url()->current() : $returnTo;
        
        $params = [
            'openid.ns'         => 'http://specs.openid.net/auth/2.0',
            'openid.mode'       => 'checkid_setup',
            'openid.return_to'  => $returnTo,
            'openid.realm'      => url('/'),
            'openid.identity'   => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
        ];
        
        $sep = ($useAmp) ? '&amp;' : '&';
        return self::STEAM_LOGIN . '?' . http_build_query($params, '', $sep);
    }

    /**
     * Validate the incoming data
     *
     * @return string Returns the SteamID64 if successful or empty string on failure
     */
    public function validateSteam()
    {
        // Start off with some basic params
        $params = [
            'openid.assoc_handle'   => $_GET['openid_assoc_handle'],
            'openid.signed'         => $_GET['openid_signed'],
            'openid.sig'            => $_GET['openid_sig'],
            'openid.ns'             => 'http://specs.openid.net/auth/2.0',
        ];
        
        // Get all the params that were sent back and resend them for validation
        $signed = explode(',', $_GET['openid_signed']);
        foreach($signed as $item) {
            $val = $_GET['openid_' . str_replace('.', '_', $item)];
            $params['openid.' . $item] = get_magic_quotes_gpc() ? stripslashes($val) : $val; 
        }

        // Finally, add the all important mode
        $params['openid.mode'] = 'check_authentication';
        
        // Stored to send a Content-Length header
        $data =  http_build_query($params);
        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => "Accept-language: en\r\n". "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen($data) . "\r\n",
                'content' => $data
            ],
        ]);

        $result = file_get_contents(self::STEAM_LOGIN, false, $context);
        
        // Validate wheather it's true and if we have a good ID
        preg_match("#^http://steamcommunity.com/openid/id/([0-9]{17,25})#", $_GET['openid_claimed_id'], $matches);
        $steamID64 = is_numeric($matches[1]) ? $matches[1] : 0;

        // Return our final value
        return preg_match("#is_valid\s*:\s*true#i", $result) == 1 ? $steamID64 : '';
    }
}
