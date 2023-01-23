<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class EtudiantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=User::with('etudiant')->get()->where('isadmin',0);
        $etudiant=[];
        foreach($user as $u)
        {
            array_push($etudiant,$u);
        }
        return response()->json($etudiant);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exeladd(Request $request)
    {
        //$request->file->store('/files');
        return response()->json(['status'=>true, $request->file]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!empty($request)){
            if ($request->password !=$request->confpassword){
                return response()->json(["error"=>"les mot de passe ne corresponde pas",510]);
            }
            if (empty($request->name) && empty($request->email ) ){
                return response()->json(["error"=>"les donnees sont vide",510]);
            }
            //store data to database
         $randodomcolor=[
                'FF6633', 'FFB399', 'FF33FF', 'FFFF99', '00B3E6', 
                  'E6B333', '3366E6', '999966', '99FF99', 'B34D4D',
                  '80B300', '809900', 'E6B3B3', '6680B3', '66991A', 
                  'FF99E6', 'CCFF1A', 'FF1A66', 'E6331A', '33FFCC',
                  '66994D', 'B366CC', '4D8000', 'B33300', 'CC80CC', 
                  '66664D', '991AFF', 'E666FF', '4DB3FF', '1AB399',
                  'E666B3', '33991A', 'CC9999', 'B3B31A', '00E680', 
                  '4D8066', '809980', 'E6FF80', '1AFF33', '999933',
                  'FF3380', 'CCCC00', '66E64D', '4D80CC', '9900B3', 
                  'E64D66', '4DB380', 'FF4D4D', '99E6E6', '6666FF'
         ];
             $img="//ui-avatars.com/api/?name=".$request->name."&size=100&rounded=true&color=fff&background=".$randodomcolor[array_rand($randodomcolor)];
         
            $newuser = new User();
            $newuser->name = $request->name;
            $newuser->email = $request->email;
            $newuser->password = Hash::make($request->password);
            $newuser->isadmin=isset($request->isadmin)?$request->isadmin:false;

            if ($newuser->save()){
                $request["user_id"] = $newuser->id;
                $request["img"] = $img;
                Etudiant::create($request->all());
            } else{
                return response()->json(["error"=>"Erreur lors de l'ajouter de l'etudiant",510]);
            }
            return response()->json(["success"=>true]);
        }
        return response()->json(["error"=>"veiller remplir tous les champs",510]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function show(Etudiant $etudiant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function Search( $word)
    {
        $user=User::with('etudiant')
        ->where('isadmin',0)
        ->where('name', 'LIKE', "%{$word}%")->get()->toArray();
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        unset($request->id);
        //update a etudiant
        $user= user::with('etudiant')->find($id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->save();
        
        $user->etudiant->classe=$request->classe;
        $user->etudiant->ecole=$request->ecole;
        $user->etudiant->address=$request->address;
        $user->etudiant->tel=$request->tel;
        $user->etudiant->save();
        return  response()->json(['success'=>true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete element in database
        $user= user::with('etudiant')->find($id);
        $user->etudiant->delete();
        $user->delete();

        return response()->json(['success'=>true]);
    }
}
