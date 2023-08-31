<?php

namespace App\Http\Controllers;

use App\Models\ProfileForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileFormController extends Controller
{
    public function index(){
        $profiles = ProfileForm::all();
        return response()->json($profiles);
    }

    public function show($id){
        $profiles = ProfileForm::findOrFail($id);
        return response()->json($profiles);
    }

    public function store(Request $request){

        $data = $request->validate([
            'name' => 'required|string',
            'username' => "required|string",
            'age' => "required|integer",
            'email' => "required|email",
            'gender' => "nullable|in:male,female",
            'tanggal_lahir' => "required|date", 
            'country' => "required|string",
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName);
            $data['profile_image'] = $imageName;
        }

        $profiles = ProfileForm::create($data);
        return response()->json($profiles, 201);
    }
    
    // public function update(Request $request, $id){

    //     $profiles = ProfileForm::findOrFail($id);

    //     $data = $request->validate([
    //         'name' => 'required|string',
    //         'username' => "required|string",
    //         'age' => "required|integer",
    //         'email' => "required|email",
    //         'gender' => "nullable|in:male,female",
    //         'tanggal_lahir' => "required|date", 
    //         'country' => "required|string",
    //         'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    //     ]);
        
    //     if ($request->hasFile('profile_image')) {
    //         $image = $request->file('profile_image');
    //         $imageName = time() . '.' . $image->getClientOriginalExtension();
    //         $image->storeAs('public/images', $imageName);
    //         $data['profile_image'] = $imageName;
    //     }
        
    //     $profiles->update($data);   
    //     return response()->json($profiles, 200);

    //     Log::info('Profile updated:', $profiles->toArray());
    //     Log::info('Update method called');
    //     Log::info('Received data:', $request->all());
        
    // }
    
    public function update(Request $request, $id){
        $profile = ProfileForm::findOrFail($id);
    
        $data = $request->validate([
            'name' => 'required|string',
            'username' => "required|string",
            'age' => "required|integer",
            'email' => "required|email",
            'gender' => "nullable|in:male,female",
            'tanggal_lahir' => "required|date",
            'country' => "required|string",
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        if($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName);
            $data['profile_image'] = $imageName;
        }
    
        $profile->update($data);
    
        return response()->json($profile, 200);
    }
    

    public function destroy($id){
        $profiles = ProfileForm::findOrFail($id);
        $profiles->delete();
        return response()->json(null, 204);
    }

}
