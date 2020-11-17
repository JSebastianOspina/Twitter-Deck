<?php

namespace App\Http\Controllers\twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Deck;

require 'twitteroauth/autoload.php';

use App\Decks_user;
use App\Http\Controllers\Controller;
use App\Rt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TwitterController extends Controller
{

    public function generar(Request $request)
    {

        $aux = Deck::where('nombre', str_replace('_', ' ', $request->input('deckname')))->first();

        $consumer_key = $aux->crearkey;
        $consumer_secret = $aux->crearsecret;
        session(['deckname' => str_replace('_', ' ', $request->input('deckname'))]);
        session(['cual' => 'api1']);
        $callback = "https://www.feed-deck.com/callback";
        define('CONSUMER_KEY', $consumer_key);
        define('CONSUMER_SECRET', $consumer_secret);
        define('OAUTH_CALLBACK', $callback);

        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

        session(['oauth_token' => $request_token['oauth_token']]);
        session(['oauth_token_secret' => $request_token['oauth_token_secret']]);

        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        return redirect($url);
    }

    public function generar1(Request $request)
    {

        $aux = Deck::where('nombre', str_replace('_', ' ', $request->input('deckname')))->first();
        session(['cual' => 'api2']);

        $consumer_key = $aux->borrarkey;
        $consumer_secret = $aux->borrarsecret;
        $callback = "https://www.feed-deck.com/callback";
        define('CONSUMER_KEY', $consumer_key);
        define('CONSUMER_SECRET', $consumer_secret);
        define('OAUTH_CALLBACK', $callback);

        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

        session(['oauth_token' => $request_token['oauth_token']]);
        session(['oauth_token_secret' => $request_token['oauth_token_secret']]);

        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        return redirect($url);
    }

    public function generar3(Request $request)
    {

        $aux = Deck::where('nombre', str_replace('_', ' ', $request->input('deckname')))->first();
        session(['cual' => 'api3']);

        $consumer_key = $aux->api3key;
        $consumer_secret = $aux->api3secret;
        $callback = "https://www.feed-deck.com/callback";
        define('CONSUMER_KEY', $consumer_key);
        define('CONSUMER_SECRET', $consumer_secret);
        define('OAUTH_CALLBACK', $callback);

        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

        session(['oauth_token' => $request_token['oauth_token']]);
        session(['oauth_token_secret' => $request_token['oauth_token_secret']]);

        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        return redirect($url);
    }


    public function callback(Request $request)
    {

        $aux = Deck::where('nombre', str_replace('_', ' ', session('nombredeck')))->first();

        $consumer_key = $aux->crearkey;
        $consumer_secret = $aux->crearsecret;

        $callback = "https://www.feed-deck.com/callback";
        define('CONSUMER_KEY', $consumer_key);
        define('CONSUMER_SECRET', $consumer_secret);
        define('OAUTH_CALLBACK', $callback);

        $request_token = [];
        $request_token['oauth_token'] = session('oauth_token');
        $request_token['oauth_token_secret'] = session('oauth_token_secret');

        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);

        $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $request['oauth_verifier']]);
        //session(['access_token' => $access_token]);



        //Guardar key en base de dstos
        $user = Auth::user();
        $guardar = Decks_user::where([['username', $user->username], ['nombredeck', session('nombredeck')]])->first();
        if (session('cual') == "api1") {
            $cuantos = Decks_user::where('twitter', $access_token['screen_name'])->count();

            if ($cuantos >= 2) {
                echo "¡OJO!, solo puedes estar en dos Decks con la misma cuenta de twitter";
                die();
            }

            $guardar->crearkey = $access_token['oauth_token'];
            $guardar->crearsecret = $access_token['oauth_token_secret'];
            $extra = $this::actualizarPerfil($access_token['screen_name']);
            $guardar->followers = $extra->followers_count;
            $guardar->img = $extra->profile_image_url_https;

            $guardar->twitter = $access_token['screen_name'];
            $guardar->save();
        } elseif (session('cual') == "api3") {
            $guardar->api3key = $access_token['oauth_token'];
            $guardar->api3secret = $access_token['oauth_token_secret'];
            $guardar->save();

        }else {
            $guardar->borrarkey = $access_token['oauth_token'];
            $guardar->borrarsecret = $access_token['oauth_token_secret'];
            $guardar->save();
        }


        //return redirect()->route('decks.show',['deck'=>session('nombredeck')]);
        //echo "<a href='" . route('decks.show', ['deck' => session('nombredeck')]) . "'>volver</a>";
        echo "<script>window.close();</script>";
    }
    public function reautorizar()
    {
        $user = Auth::user();
        $guardar = Decks_user::where([['username', $user->username], ['nombredeck', session('nombredeck')]])->first();
        $guardar->twitter = "";
        $guardar->save();
        echo "<script>window.close();</script>";
    }

    public function darRT(Request $request)
    {
        if (Auth::user()->hasRole('Owner')) {
            $aux = Deck::where('nombre', str_replace('_', ' ', $request->input('deckname')))->first();

            $consumer_key = $aux->crearkey;
            $consumer_secret = $aux->crearsecret;
            $callback = "https://www.feed-deck.com/callback";

            define('OAUTH_CALLBACK', $callback);

            $tweet = $request->input('rtid');
            $deck_name = $request->input('deckname');
            $guardarr = Decks_user::where(['nombredeck' => $deck_name])->get();
            $contador = 0;
            $total = 0;
            $quienes = [];
            $no = "";
            foreach ($guardarr as $guardar) {
                $access_token = [];
                $access_token['oauth_token'] = $guardar->crearkey;
                $access_token['oauth_token_secret'] = $guardar->crearsecret;
                $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
                // obtener datos usuario user = $connection->get('account/verify_credentials', ['tweet_mode' => 'extended', 'include_entities' => 'true']);

                $statues = $connection->post("statuses/retweet", ["id" => $tweet]);
                //echo $guardar->twitter;
                //echo "\n respuesta: \n ";
                //print_r ($statues);
                //echo "Codigo: ".$connection->getLastHttpCode();

                //exit();
                if ($this::isError($statues) == "no") {
                    $contador++;
                } else {
                    $no = $no . $guardar->twitter . ",";
                    $c = new \stdClass();
                    $c->twitter = $guardar->twitter;
                    $c->codigo = $statues->errors[0]->code;
                    $c->mensaje = $statues->errors[0]->message;
                    array_push($quienes, $c);
                    //if ($statues->errors[0]->code == "89") {
                    // echo "Lo logramos";

                    //$quienes $guardar->twitter

                    //}
                }
                $total++;
            }

            $registro = new Rt;
            $registro->rtid = $tweet;
            $registro->deck = $deck_name;
            $registro->cuenta = Auth::user()->username;
            $registro->twitter = $no; //TERMINAR
            $registro->pendiente = "Si";
            $registro->cantidad = $contador . '/' . $total;
            $registro->quienes = serialize($quienes);
            $registro->save();


            return back()->with('total', $contador . '/' . $total);
        }
        $causante = Decks_user::where([['username', Auth::user()->username], ['nombredeck', $request->input('deckname')]])->first();
        $posible = Rt::where([['cuenta', $causante->username], ['deck', $request->input('deckname')]])->latest()->first();
        if ($posible != null) {

            if (($posible->created_at->diff(Carbon::now())->h) < 1) {
                return back()->withErrors('Haz alcanzado el máximo de RT/H');
            }
        }

        if ($causante->crearsecret == null || $causante->twitter == "") {
            return back()->withErrors('¿Estas tratando de dar RT sin tener apis aprobadas?');
        } else {
            $aux = Deck::where('nombre', str_replace('_', ' ', $request->input('deckname')))->first();
            if ($aux->api3key == null) {
                $consumer_key = $aux->crearkey;
                $consumer_secret = $aux->crearsecret;
            } 
            else {
                if ($aux->numero == null | $aux->numero == 1) {
                    $consumer_key = $aux->crearkey;
                    $consumer_secret = $aux->crearsecret;
                    $aux->numero = 2;
                    $controlador = $aux->numero;
                    $aux->save();
                    
                } else {
                    $consumer_key = $aux->api3key;
                    $consumer_secret = $aux->api3secret;
                    $aux->numero = 1;
                    $aux->save();
                }
            }
            $controlador;

            $controlador =  $aux->numero;
            


            $callback = "https://www.feed-deck.com/callback";

            define('OAUTH_CALLBACK', $callback);

            $tweet = $request->input('rtid');
            $deck_name = $request->input('deckname');
            $guardarr = Decks_user::where(['nombredeck' => $deck_name])->get();
            $contador = 0;
            $total = 0;
            $quienes = [];
            $no = "";
            foreach ($guardarr as $guardar) {

                $access_token = [];
                if($controlador == 2) { 
                $access_token['oauth_token'] = $guardar->crearkey;
                $access_token['oauth_token_secret'] = $guardar->crearsecret;
                } elseif($controlador == null){
                
                $access_token['oauth_token'] = $guardar->crearkey;
                $access_token['oauth_token_secret'] = $guardar->crearsecret;
                }
                else{
                    
                    $access_token['oauth_token'] = $guardar->api3key;
                    $access_token['oauth_token_secret'] = $guardar->api3secret;
                }
                
                $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
                // obtener datos usuario user = $connection->get('account/verify_credentials', ['tweet_mode' => 'extended', 'include_entities' => 'true']);

                $statues = $connection->post("statuses/retweet", ["id" => $tweet]);
                //echo $guardar->twitter;
                //echo "\n respuesta: \n ";
                //print_r ($statues);
                //echo "Codigo: ".$connection->getLastHttpCode();

                //exit();
                if ($this::isError($statues) == "no") {
                    $contador++;
                } else {
                    $no = $no . $guardar->twitter . ",";
                    $c = new \stdClass();
                    $c->twitter = $guardar->twitter;
                    $c->codigo = $statues->errors[0]->code;
                    $c->mensaje = $statues->errors[0]->message;
                    array_push($quienes, $c);
                    //if ($statues->errors[0]->code == "89") {
                    // echo "Lo logramos";

                    //$quienes $guardar->twitter

                    //}
                }
                $total++;
            }

            $registro = new Rt;
            $registro->rtid = $tweet;
            $registro->deck = $deck_name;
            $registro->cuenta = Auth::user()->username;
            $registro->twitter = $no; //TERMINAR
            $registro->pendiente = "Si";
            $registro->cantidad = $contador . '/' . $total;
            $registro->quienes = serialize($quienes);
            $registro->save();


            return back()->with('total', $contador . '/' . $total);
        }
    }
    public function isError($objeto)
    {
        if (isset($objeto->errors[0]->message)) {
            return "si";
        } else {
            return "no";
        }
    }

    public function actualizarPerfil($objeto)
    {


        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

        $statues = $connection->get("users/show", ["id" => $objeto]);



        return $statues;
    }
    public function hora()
    {
        $callback = "https://www.feed-deck.com/callback";
        define('OAUTH_CALLBACK', $callback);

        $tweet = Rt::where('deck', 'SocialBoost')->latest()->get();

        foreach ($tweet as $posible) {
            $diferencia = $posible->updated_at->diff(Carbon::now());

            if (($diferencia->h > 1) || ($diferencia->i > 10) || 1 == 1) { //HA pasado mas de diez minutos

                $deck_name = $posible->deck;
                $aux = Deck::where('nombre', str_replace('_', ' ', $deck_name))->first();
                $consumer_key = $aux->borrarkey;
                $consumer_secret = $aux->borrarsecret;

                $borrame = Decks_user::where('nombredeck', $deck_name)->get(); //Tomamos los users del deck
                $infractores = [];
                $no = "";
                foreach ($borrame as $guardar) {
                    $access_token = [];
                    $access_token['oauth_token'] = $guardar->borrarkey;
                    $access_token['oauth_token_secret'] = $guardar->borrarsecret;
                    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

                    $statues = $connection->post("statuses/unretweet", ["id" => $posible->rtid]);
                    echo "borrado" . $guardar->username . "\n";


                    if (isset($statues->errors[0])) {
                        $echo = "Se ha detectado un error de api con: " . $guardar->twitter;
                    } else {

                        if ($statues->retweeted == false) {
                            $no = $no . $guardar->twitter . ",";

                            /*
                            $c->codigo = $statues->errors[0]->code;
                            $c->mensaje = $statues->errors[0]->message;
                            array_push($infractores,$c);
    */
                            echo "vamos bien";
                        }
                    }
                }


                $posible->infractores = $no;
                $posible->save();
            }
        }

        echo "listo";
    }
    public function unrt()
    {
        $callback = "https://www.feed-deck.com/callback";
        define('OAUTH_CALLBACK', $callback);

        $tweet = Rt::where('pendiente', 'Si')->get();
        foreach ($tweet as $posible) {
            $diferencia = $posible->updated_at->diff(Carbon::now());

            if (($diferencia->h > 1) || ($diferencia->i > 10)) { //HA pasado mas de diez minutos

                $deck_name = $posible->deck;
                $aux = Deck::where('nombre', str_replace('_', ' ', $deck_name))->first();

                $consumer_key = $aux->borrarkey;
                $consumer_secret = $aux->borrarsecret;

                $borrame = Decks_user::where('nombredeck', $deck_name)->get();

                //Tomamos los users del deck
                $no = "";
                foreach ($borrame as $guardar) {
                    //if(!($guardar->nombredeck == "MM_Deck")){



                    $access_token = [];
                    $access_token['oauth_token'] = $guardar->borrarkey;
                    $access_token['oauth_token_secret'] = $guardar->borrarsecret;
                    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

                    $statues = $connection->post("statuses/unretweet", ["id" => $posible->rtid]);
                    var_dump($statues);

                    echo "borrado" . $guardar->username . "\n";


                    //DETECCION DE INFRACTORES
                    if (isset($statues->errors[0])) {
                    } else {

                        if ($statues->retweeted == false) {
                            $no = $no . $guardar->twitter . ",";
                        }
                    }


                    //FINALIZA LA DETECCION DE INFRACTORES
                }

                $posible->pendiente = "No";
                $posible->infractores = $no;

                $posible->save();
            }
        }

        // }

        echo "listo";
    }


    public function limite($id)
    {
        $aux = Deck::where('nombre', str_replace('_', ' ', $id))->first();

        $consumer_key = $aux->crearkey;
        $consumer_secret = $aux->crearsecret;

        $guardar = Decks_user::where([['nombredeck', $id], ['username', Auth::user()->username]])->first();

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $guardar->crearkey, $guardar->crearsecret);
        // obtener datos usuario user = $connection->get('account/verify_credentials', ['tweet_mode' => 'extended', 'include_entities' => 'true']);
        $statues = $connection->get("application/rate_limit_status", ["resources" => "statuses"]);
        var_dump($statues);
        echo "hi";
    }
}
