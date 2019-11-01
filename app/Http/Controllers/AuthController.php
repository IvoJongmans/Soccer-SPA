<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DB;
use App\User;
use App\Team;
use App\Player;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        //validate request
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        //return errors upon validation fail
        if ($validator->fails()) {
            return $validator->messages();
        }

        //get user from request
        $user = User::where('email', $request['email'])->first();

        //check request password against user password
        $validCredentials = Hash::check($request['password'], $user['password']);

        if ($validCredentials) {

            //create bearer token when user is verified
            $http = new Client();

            $response = $http->post('http://localhost/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 1,
                    'client_secret' => '0sivt8YSI9KRdh8snvuzMpFabiFGLVqtT5dw2cu9',
                    'username' => $request['email'],
                    'password' => $request['password'],
                    'scope' => '',
                ],
            ]);

            return json_decode((string) $response->getBody(), true);

        }
        //no user found with matching credentials -> return error with message
        else {
            $no_user =  array(
                'error' => 'invalid_user',
                'message' => 'The combination of email address and password you entered does not match.'
            );

            return json_encode($no_user);
        }
    }

    public function logout()
    {
        //request token and revoke it
        $token = request()->user()->token();

        DB::table('oauth_access_tokens')
            ->where('id', $token->id)
            ->update([
                'revoked' => true
            ]);

        return "token_revoked";
    }

    public function team()
    {
        //test to see if i can retrieve a team from hasOne relation
        $team = request()->user()->team;

        if($team) {
            return $team->team_name;
        }
        else {
            return "no_team";
        }
    }

    public function create_team(Request $request) {



        $user_id = request()->user()->id;

        $team_name = $request['team_name'];

        $validator = Validator::make($request->all(), [
            'team_name' => ['required', 'string', 'max:255', 'unique:teams']
        ]);

        //return errors upon validation fail
        if ($validator->fails()) {
            return $validator->messages();
        }

        $new_team = new Team();

        $new_team->team_name = $team_name;
        $new_team->user_id = $user_id;

        $new_team->save();

        return $request['team_name'];
    }

    public function getPlayers() {

        $players = Player::all();

        $user_players = request()->user()->team->players;

        $players_id = [];

        $user_players_id = [];

        for($i = 0; $i < count($players); $i++) {
            array_push($players_id, $players[$i]->id);
        }

        for($i = 0; $i < count($user_players); $i++) {
            array_push($user_players_id, $user_players[$i]->id);
        }

        for($i = 0; $i < count($user_players_id); $i++) {
            for($x = 0; $x < count($players); $x++) {
                if($user_players_id[$i] == $players[$x]->id ) {
                    // return $user_player_id[$i];
                }
            }
        }

        $players_unique = array_diff($players_id, $user_players_id);

        $players_to_return = Player::find($players_unique);







        return $players_to_return;
        // return json_encode($players);
    }

    public function edit_players(Request $request){


        $team = request()->user()->team;
        $old_players = $team->players;
        $old_players_id = [];

        // for($i = 0; $i < count($old_players); $i++) {
        //     array_push($old_players_id, $old_players[$i]->id);
        // };

        $team->players()->detach($old_players);

        $player_array = $request->all();
        $players = Player::find($player_array);
        $team->players()->attach($players);

        return "Edit successful!";
    }

    public function retrieve_players() {
        $players = request()->user()->team->players;

        return $players;
    }

}
