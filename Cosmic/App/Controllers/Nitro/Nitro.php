<?php
namespace App\Controllers\Nitro;

use App\Core;
use App\Config;
use App\Token;

use App\Models\Api;
use App\Models\Ban;
use App\Models\Player;
use App\Models\Room;

use Core\Locale;
use Core\View;

use Library\HotelApi;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use stdClass;

class Nitro
{
    private $data;
    private $record;

    public function client()
    {
        $this->data = new stdClass();

        $user = Player::getDataById(request()->player->id);
      
        $this->data->auth_ticket = Token::authTicket($user->id);
        $this->data->unique_id = sha1($user->id . '-' . time());

        Player::update($user->id, ["auth_ticket" => $this->data->auth_ticket]);
      
        if ($user->getMembership()) {
            HotelApi::execute('setrank', ['user_id' => $user->id, 'rank' => $user->getMembership()->old_rank]);
            $user->deleteMembership();
        }

        View::renderTemplate('Client/nitro.html', [
            'title' => Locale::get('core/title/hotel'),
            'room' => explode("=", url()->getOriginalUrl())[1] ?? null,
            'data'  => $this->data,
            'client' => Config::client,
            'site' => Config::site
        ]);
    }

    public function hotel()
    {
        $this->data = new stdClass();

        $user = Player::getDataById(request()->player->id);
      
        $this->data->auth_ticket = Token::authTicket($user->id);
        $this->data->unique_id = sha1($user->id . '-' . time());

        Player::update($user->id, ["auth_ticket" => $this->data->auth_ticket]);
      
        if ($user->getMembership()) {
            HotelApi::execute('setrank', ['user_id' => $user->id, 'rank' => $user->getMembership()->old_rank]);
            $user->deleteMembership();
        }

        View::renderTemplate('Client/nitro.html', [
            'title' => Locale::get('core/title/hotel'),
            'room' => explode("=", url()->getOriginalUrl())[1] ?? null,
            'data'  => $this->data,
            'client' => Config::client,
            'site' => Config::site
        ]);
    }

    public function count()
    {
        echo \App\Models\Core::getOnlineCount();
        exit;
    }
}
