<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DataTables;

class UsersController extends Controller
{

    public function index(Request $request)
    {
        $users = User::latest()->get();
        // echo "<pre>";
        // print_r($data);
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';
   
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('user',compact('users'));

    }


    public function create()
    {
        //
    }

 
    public function store(Request $request)
    {
        //
        // echo "<pre>";
        // print_r($request->post());
        User::updateOrCreate(['id' => $request->user_id],
                ['name' => $request->name, 'email' => $request->email, 'phone' => $request->number, 'city' => $request->city, 'gender' => $request->gender]);        
   
        return response()->json(['success'=>'Book saved successfully.']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        User::find($id)->delete();
     
        return response()->json(['success'=>'User deleted successfully.']);
    }
}
