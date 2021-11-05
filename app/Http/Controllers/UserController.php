<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use App\Transformers\UserTransformer;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::paginate(10);
        return fractal($users, new UserTransformer())->respond();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //display form
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:10',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
        ]);

        $user = new User;

        if($user){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json(['message'=>'New user created succesfully'], 200);
        }else{
            return response()->json(['message'=>'New user could not be registered'], 404);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = User::findorFail($id);

        if($user){
            return fractal($user, new UserTransformer())->respond();
        }else{
            return response()->json(['message'=>'User info not found'], 404);
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //display edit form
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'name' => 'required|max:10',
            'email' => 'required|unique:users|email',
        ]);


        $user = User::findorFail($id);

        if($user){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->update();

            return response()->json(['message'=>'User info updated succesfully'], 200);
        }else{
            return response()->json(['message'=>'User info not found'], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findorFail($id);

        if($user){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->delete();

            return response()->json(['message'=>'User info deleted succesfully'], 200);
        }else{
            return response()->json(['message'=>'User info not found'], 404);
        }
    }

    //import and export functions

    public function fileImport(Request $request)
    {
        $users = Excel::import(new UsersImport(), $request->file('file')->store('temp'));
        return response()->json(['message'=>"Excel file uploaded succesfully"]);
    }

    public function fileUpdatedImport(Request $request)
    {
        $users = Excel::toCollection(new UsersImport(), $request->file('file')->store('temp'));
        
        foreach ($users[0] as $user) {
            User::where('id', $user['id'])->update([
                'name' => $user['name'],
                'email' => $user['email'],
            ]);
        }
        return response()->json(['message'=>"Updated excel file uploaded succesfully"]);
    }

    public function fileExport() 
    {
        return Excel::download(new UsersExport, 'users-collection.xlsx');
        
    }    
}
