<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::query()->when($request->search,function(Builder $query) use($request){
                        $query->where('name','LIKE','%'.$request->search.'%')
                        ->orWhere('mobile','LIKE','%'.$request->search.'%')
                        ->orWhere('email','LIKE','%'.$request->search.'%');
                    })->latest()->paginate(5);
        return Inertia::render('User/ViewUser', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        $response = User::updateOrCreate($validatedData);
        if($request->has('image')) {
            $user = User::findOrFail($response->id);
            $user->image = $request->file('image')->store('uploads','public');
            $user->save();
        }
        return to_route('users.index')->with('success','User added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user,UpdateUserRequest $request)
    {
        $validatedData = $request->validated();

        if($validatedData['password'] !='') {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }else{
            $validatedData['password'] = $user->password;
        }

        if($validatedData['image'] !='') {
            $validatedData['image'] = $request->file('image')->store('uploads','public');
        }else{
            $validatedData['image'] = $user?->image;
        }

        $response = User::where('id',$user->id)->update($validatedData);
        return to_route('users.index')->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return to_route('users.index')->with('success','Data deleted successfully');
    }

    public function pdf(Request $request)
    {
        $users = User::latest()->get();
        $pdf = PDF::loadView('users_pdf', compact('users'));
        return $pdf->download('users_pdf.pdf');
    }

    public function updateStatus(User $user)
    {
        $user->is_active = !$user?->is_active;
        $user->save();
        return to_route('users.index')->with('success','User status changed successfully');
    }
}
