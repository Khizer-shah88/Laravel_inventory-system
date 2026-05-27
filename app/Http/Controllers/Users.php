<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UsersAll;

class Users extends Controller
{
    public function index(){
        
        $users = DB::table('users')->get();
        
        
        if (session()->get('role') == 1){
        return view('/Users/index',compact('users'));
        }
        else{
        return view('/restricted');
        }
    }
    
    public function edit($id){
        
        $record = DB::table('users')->find($id);
        //$users_sub = UsersAll::all()->groupBy('menu_type');
        $users_sub = DB::table('tbl_users_sub')->where('user_id',$id)->orderBy('menu_type')->get();
        $items = DB::table('tbl_users_sub')->where('user_id',$id)->get();
        
        return view('/Users/edit',compact('record','users_sub','items'));
        //return $users_sub;
    }
    
    public function update($id, Request $req){
        
        

        DB::table('users')->where('id',$id)
            ->update(
        ['password' => $req->password,
        'name' => $req->name,
        'role' => $req->role
        ]
       
    );

    $sub_id = $req->user_id;
    $user_read = $req->read_val;
    $user_add = $req->add_val;
    $user_edit = $req->edit_val;
    $user_delete = $req->delete_val;


    
    for ($i=0; $i<count($user_read); $i++) {


            
             $datasave =  [
                          
              'user_read' => $user_read[$i],
              'user_add' => $user_add[$i],
              'user_edit' => $user_edit[$i],
              'user_delete' => $user_delete[$i],


            ];
            
            DB::table('tbl_users_sub')->where('id',$sub_id[$i])->update($datasave); 
            
          
           }
    $users = DB::table('users')->get();
        
    return view('/Users/index',compact('users'));
    
    }
    
    public function open(){
        return view('/Users/add');
        }
    
    public function addNew(Request $req){
        
        $lastID = DB::table('users')->insertGetId([
            'name' => $req->name,
            'password' => $req->password,
            'role' => $req->role
        ]);
        
        DB::table('tbl_users_sub')->insert([
            ['user_read' => 1, 'user_add' => 1, 'user_edit' => 0, 'user_delete' => 0, 'user_id' => $lastID, 'menu_type' => 'FORMS', 'menu_name' => 'Add Items'],
            ['user_read' => 1, 'user_add' => 1, 'user_edit' => 0, 'user_delete' => 0, 'user_id' => $lastID, 'menu_type' => 'REPORTS','menu_name' => 'Current Stock Position'],


            
        ]);     
        
        $users = DB::table('users')->get();
        return view('/Users/index',compact('users'));

    
    

    }
    
    public function delete($id, Request $req){
                        
                    DB::table('users')->where('id', $id)->delete();
        DB::table('tbl_users_sub')->where('user_id', $id)->delete();
        $users = DB::table('users')->get();
        return view('/Users/index',compact('users'));
    }
    
}