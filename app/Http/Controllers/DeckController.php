<?php

namespace App\Http\Controllers;

use App\Deck;
use App\Decks_user;
use App\Noticia;
use App\Rt;
use App\User;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class DeckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Auth::user()->hasRole(['Owner'])) {
            $decks = Deck::all();

        } else {
            $deckk = Decks_user::where('username', Auth::user()->username)->get();
            $decks = array();
            $contador = 0;
            foreach ($deckk as $nombre) {

                array_push($decks, Deck::where('nombre', str_replace('_', ' ', $nombre->nombredeck))->first());

            }

        }

        // echo "salto \n";
        //$decks = Deck::all();
        //print_r($decks);

        return view('panel.deck.decks', compact('decks'));
    }

    public function historial($id)
    {
        $histo = Rt::where('deck', $id)->orderBy('created_at', 'desc')->limit(10)->get();

        return view('panel.deck.historial', compact('histo', 'id'));
    }

    public function inspector($id,$unico)
    {
        $histo = Rt::find($unico);
        $o = [];
        $h = "1277402467278430208"; 
        $o = \unserialize($histo->quienes);
        return view('panel.deck.inspector', compact('o', 'id','h'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|unique:decks',
            'admin' => 'required',
            'descipcion' => 'required',

        ]);

        $usertemp = User::where('username', $request->input('admin'))->first();
        if ($usertemp != null) {
            $registro = new Deck;
            $registro->nombre = $request->input('nombre');
            $registro->admin = $request->input('admin');
            $registro->descripcion = $request->input('descipcion');
            $registro->rt = $request->input('rt');
            $registro->save();
            //crear los permisos
            $role = Role::create(['name' => str_replace(' ', '_', $request->input('nombre'))]);
            $role = Role::create(['name' => 'admin-' . str_replace(' ', '_', $request->input('nombre'))]);

            $usertemp->assignRole("Admin");
            $usertemp->assignRole('admin-' . str_replace(' ', '_', $request->input('nombre')));

            return back();
        } else {
            return back()->withErrors('¡Cuidado! ese nombre de usuario no existe.');
        }
    }

    public function noticias()
    {
        $noticias = Noticia::latest()->get();
        return view('panel.deck.noticias', compact('noticias'));

    }

    public function noticiasCrear(Request $request)
    {
        $noticias = new Noticia;
        $noticias->titulo = $request->input('titulo');
        $noticias->descripcion = $request->input('descripcion');
        $noticias->img = $request->input('img');
        $noticias->save();
        return back();

    }

    public function show($id)
    {
        $decks = Decks_user::where('nombredeck', $id)->orderBy('followers', 'desc')->get();

        if (Auth::user()->hasRole(['Owner', $id, 'admin-' . $id])) {
            $contador = 0;
            foreach ($decks as $v) {
                $contador += $v->followers;
            }
            session(['nombredeck' => $id]); //guardamos
            $cred = Deck::where('nombre', str_replace('_', ' ', $id))->first();
            $alv = User::role('admin-' . $id)->get();
            $admins = [];
            foreach($alv as $aux){
              $admins[] = $aux->username;
            }
            return view('panel.deck.deck', compact('decks', 'id', 'cred', 'contador','admins'));

        } else {
            abort(403);

        }

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { //$decks= DB::table('decks')->first()->where('nombre',str_replace('_',' ',$id));
        $deck = Deck::where('nombre', str_replace('_', ' ', $id))->first();

        $deck->crearkey = $request->input('key1');
        $deck->crearsecret = $request->input('secret1');
        $deck->borrarkey = $request->input('key2');
        $deck->borrarsecret = $request->input('secret2');
        if ($request->input('key3') != "" && $request->input('secret3') != "") {
            $deck->api3key = $request->input('key3');
            $deck->api3secret = $request->input('secret3');
        }
        $deck->whatsapp = $request->input('whatsapp');

        $deck->save();
        return back();
    }

    public function newUser(Request $request, $id)
    {

        $user = User::where('username', $request->input("username"))->first();
        if (!($user == null)) {

            $registro = new Decks_user;
            $registro->nombredeck = $id;
            $registro->username = $request->input("username");
            $registro->img = "";
            $registro->save();

            $user->assignRole($id);
            return back();

        } else {
            return back()->with('error', '¡Cuidado! ese usuario no está registrado');

        }

    }

    public function newAdmin(Request $request, $id)
    {
        $user = User::where('username', $request->input("username"))->first();
        if (!($user == null)) {
            if ($request->input("accion") == "Añadir") {
                $user->assignRole('admin-' . $id);
                return back();
            } else {
                $user->removeRole('admin-' . $id);
                return back();

            }

        } else {
            return back()->with('error', '¡Cuidado! ese usuario no está registrado');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $borrar = Deck::where('nombre', str_replace('_', ' ', $id))->first();
        $borrar->delete();
        Role::findByName($id)->delete();
        Role::findByName('admin-' . $id)->delete();

        return redirect()->route('decks.index');

    }

    public function eliminarUser(Request $request)
    {

        $registro = Decks_user::where([['username', $request->input("username")], ['nombredeck', $request->input("deck-name")]])->first();
        $dale = $request->input("user-id");

        $registro->delete();
        $user = User::where('username', $request->input("username"))->first();
        $user->removeRole($request->input("deck-name"));

        return back()->with('mensaje', 'Usuario con Twitter ' . $dale . ' eliminado exitosamente');
    }
    public function cache (){

        $exitCode = Artisan::call('config:cache');
        $exitCode = Artisan::call('route:cache');
        //$exitCode = Artisan::call('view:clear');
      //  $exitCode = Artisan::call('cache:clear');
       // $exitCode = Artisan::call('migrate:rollback --step=1');
    
        // $exitCode = Artisan::call('migrate');
        
    
        return '<h1>Clear Config cleared</h1>';

    }

   

}
