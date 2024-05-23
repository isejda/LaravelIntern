<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModalFormRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;

//use Yajra\DataTables\Facades\DataTables;

class UsersControllerCopy extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     */

    public function tree(): array|\Illuminate\Contracts\Auth\Authenticatable
    {
        $loggedInUser = auth()->user();

        if (!$loggedInUser) {
            return [];
        }

        $allUsers = User::where('role', '!=', 'admin')
            ->where('id', '!=', auth()->user()->id)
            ->get();

        self::formatTree($loggedInUser, $allUsers);

        return $loggedInUser;
    }


    //funksion vetem per te marre femijet direkt te userit.
 /*   private static function formatTree($user, $allUsers): void
    {
        $user->children = $allUsers->where('parent_id', $user->id)->values();

        $user->children->each(function ($child) use ($allUsers) {
            self::formatTree($child, $allUsers);
        });

    }*/

    private static function formatTree($user, $allUsers): void
    {
        $directChildren = $allUsers->where('parent_id', $user->id)->values();
        $allChildren = [];

        foreach ($directChildren as $child) {
            self::formatTree($child, $allUsers);
            $allChildren[] = $child;
            $allChildren = array_merge($allChildren, $child->children->toArray());
        }
        $user->children = collect($allChildren);
    }


//   private static function formatTree($users, $allUsers)
//    {
//        foreach ($users as $user){
//            $user ->children = $allUsers->where('parent_id', $user->id)->values();
//
//            if ($user->children->isNotEmpty()){
//                self::formatTree($user -> children, $allUsers);
//            }
//
//        }
//    }

    /**
     * @throws Exception
     */
    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        $tree = $this->tree()->toArray();

        $children = collect($tree['children'])->map(function ($child) {
            return (object) $child;
        });

        if ($children->isEmpty()) {
            abort(404);
        }

        if ($request->ajax()) {
            return Datatables::of($children)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $isAdmin = $user->role === 'admin';
                    $isManager = $user->role === 'manager';

                    if ($isAdmin) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';
                        $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</a>';
                        return $btn;
                    } else if ($isManager) {
                        return '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';
                    } else {
                        return '<em>Not Allowed</em>';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index', compact('children'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(ModalFormRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            // Update user data
            $user->name = $validatedData['name'];
            $user->lastname = $validatedData['lastname'];
            $user->email = $validatedData['email'];
            $user->username = $validatedData['username'];
            $user->birthday = $validatedData['birthday'];
            $user->role = $validatedData['role'];

            if ($request->filled('password')) {
                $user->password = bcrypt($validatedData['password']);
            }

            $user->save();

            return response()->json(['success' => 'User updated successfully.']);
        } else {
            $userData = $validatedData;
            $userData['password'] = bcrypt($validatedData['password']);
            $userData['parent_id'] = auth()->user()->id;

            User::create($userData);
            return response()->json(['success' => 'User created successfully.']);
        }
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
    public function edit(string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        User::find($id)->delete();
        return response()->json(['success'=>'User deleted successfully.']);
    }

}
