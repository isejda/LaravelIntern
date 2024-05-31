<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModalFormRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;

//use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
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
            return (object)$child;
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
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';
                        $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</a>';
                        return $btn;
                    } else if ($isManager) {
                        return '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';
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


    public function store(UserStoreRequest $request): JsonResponse
    {
            $validatedData = $request->validated();
            $userData = $validatedData;
            $userData['password'] = bcrypt($validatedData['password']);
            $userData['parent_id'] = auth()->user()->id;

            User::create($userData);
            return response()->json(['success' => 'User created successfully.']);
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
    public function edit($id)
    {
        return User::findOrFail($id);
    }


    /**
     * Update the specified resource in storage.
     */

//    public function update(UserUpdateRequest $request, string $i): JsonResponse
//    {
//        dd('update');
//        $validatedData = $request->validated();
//        $user = User::find($id);
//        if (!$user) {
//            return response()->json(['error' => 'User not found.'], 404);
//        }
//
//        // Update user data
//        $user->name = $validatedData['name'];
//        $user->lastname = $validatedData['lastname'];
//        $user->email = $validatedData['email'];
//        $user->username = $validatedData['username'];
//        $user->birthday = $validatedData['birthday'];
//        $user->role = $validatedData['role'];
//
//        if ($request->filled('password')) {
//            $user->password = bcrypt($validatedData['password']);
//        }
//
//        $user->save();
//
//        return response()->json(['success' => 'User updated successfully.']);
//    }

    public function update(UserUpdateRequest $request, string $id): JsonResponse
    {
        $user = User::find($id);
        $validatedData = $request->validated();
        $user->fill($validatedData);
        $user->save();

        return response()->json(['success' => 'User updated successfully.']);
    }
/*    public function update(Request $request, string $id)
    {
        //
    }*/

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        User::find($id)->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }

    /**
     * @throws Exception
     */

    public function fetchUserYears(Request $request)
    {
        if ($request->operation == "years") {
            $username = $request->username;

            $user = User::with('hyrjeDaljeModel')->where('username', $username)->first();

            if (!$user) {
                return response()->json([]);
            }

            $formattedData = [];
            $yearlyDuration = [];

            foreach ($user->hyrjeDaljeModel as $hyrjeDalje) {
                $timeHyrje = new DateTime($hyrjeDalje->ora_hyrje);
                $timeDalje = new DateTime($hyrjeDalje->ora_dalje);

                if ($timeHyrje->format('H:i:s') == '00:00:00'){
                    $timeHyrje->setTime(24, 0, 0);
                }
                if ($timeDalje->format('H:i:s') == '00:00:00'){
                    $timeDalje->setTime(24, 0, 0);
                }

                $difference = $timeDalje->getTimestamp() - $timeHyrje->getTimestamp();

                $hours = floor($difference / 3600);
                $minutes = floor(($difference % 3600) / 60);
                $seconds = $difference % 60;

                $dateHyrje = new DateTime($hyrjeDalje->data_hyrje);
                $year = $dateHyrje->format('Y');

                if (!isset($yearlyDuration[$year])) {
                    $yearlyDuration[$year] = ['hours' => 0, 'minutes' => 0, 'seconds' => 0];
                }
                $yearlyDuration[$year]['hours'] += $hours;
                $yearlyDuration[$year]['minutes'] += $minutes;
                $yearlyDuration[$year]['seconds'] += $seconds;
            }


            foreach ($yearlyDuration as $year => $duration) {
                if ($duration['seconds'] >= 60) {
                    $duration['minutes'] += floor($duration['seconds'] / 60);
                    $duration['seconds'] %= 60;
                }

                if ($duration['minutes'] >= 60) {
                    $duration['hours'] += floor($duration['minutes'] / 60);
                    $duration['minutes'] %= 60;
                }

                $formattedData[] = [
                    'username' => $username,
                    'year' => $year,
                    'hours' => $duration['hours'],
                    'minutes' => $duration['minutes'],
                    'seconds' => $duration['seconds']
                ];
            }
            return response()->json($formattedData);
        }

        if ($request->operation == "months") {
            $username = $request->username;
            $year = $request->year;

            $user = User::with('hyrjeDaljeModel')->where('username', $username)->first();

            if (!$user) {
                return response()->json([]);
            }

            $formattedData = [];
            $monthlyDuration = [];

            // Calculate monthly durations
            foreach ($user->hyrjeDaljeModel as $hyrjeDalje) {
                $timeHyrje = new DateTime($hyrjeDalje->ora_hyrje);
                $timeDalje = new DateTime($hyrjeDalje->ora_dalje);

                if ($timeHyrje->format('H:i:s') == '00:00:00'){
                    $timeHyrje->setTime(24, 0, 0);
                }
                if ($timeDalje->format('H:i:s') == '00:00:00'){
                    $timeDalje->setTime(24, 0, 0);
                }

                $difference = $timeDalje->getTimestamp() - $timeHyrje->getTimestamp();

                $hours = floor($difference / 3600);
                $minutes = floor(($difference % 3600) / 60);
                $seconds = $difference % 60;

                $dateHyrje = new DateTime($hyrjeDalje->data_hyrje);
                $month = $dateHyrje->format('n');

                if ($dateHyrje->format('Y') == $year) {
                    if (!isset($monthlyDuration[$month])) {
                        $monthlyDuration[$month] = ['hours' => 0, 'minutes' => 0, 'seconds' => 0];
                    }
                    $monthlyDuration[$month]['hours'] += $hours;
                    $monthlyDuration[$month]['minutes'] += $minutes;
                    $monthlyDuration[$month]['seconds'] += $seconds;
                }
            }

            // Format the data for each month
            foreach ($monthlyDuration as $month => $duration) {
                if ($duration['seconds'] >= 60) {
                    $duration['minutes'] += floor($duration['seconds'] / 60);
                    $duration['seconds'] %= 60;
                }

                if ($duration['minutes'] >= 60) {
                    $duration['hours'] += floor($duration['minutes'] / 60);
                    $duration['minutes'] %= 60;
                }

                $formattedData[] = [
                    'month' => $month,
                    'hours' => $duration['hours'],
                    'minutes' => $duration['minutes'],
                    'seconds' => $duration['seconds']
                ];
            }

            return response()->json($formattedData);
        }

        if ($request->operation == "days") {
            $username = $request->username;
            $year = $request->year;
            $month = $request->month;

            $user = User::with('hyrjeDaljeModel')->where('username', $username)->first();

            if (!$user) {
                return response()->json([]);
            }

            $formattedData = [];
            $dailyDuration = [];

            // Calculate daily durations
            foreach ($user->hyrjeDaljeModel as $hyrjeDalje) {
                $timeHyrje = new DateTime($hyrjeDalje->ora_hyrje);
                $timeDalje = new DateTime($hyrjeDalje->ora_dalje);

                if ($timeHyrje->format('H:i:s') == '00:00:00'){
                    $timeHyrje->setTime(24, 0, 0);
                }
                if ($timeDalje->format('H:i:s') == '00:00:00'){
                    $timeDalje->setTime(24, 0, 0);
                }

                $difference = $timeDalje->getTimestamp() - $timeHyrje->getTimestamp();

                $hours = floor($difference / 3600);
                $minutes = floor(($difference % 3600) / 60);
                $seconds = $difference % 60;

                $dateHyrje = new DateTime($hyrjeDalje->data_hyrje);
                $day = $dateHyrje->format('j');

                if ($dateHyrje->format('Y') == $year && $dateHyrje->format('n') == $month) {
                    if (!isset($dailyDuration[$day])) {
                        $dailyDuration[$day] = ['hours' => 0, 'minutes' => 0, 'seconds' => 0];
                    }
                    $dailyDuration[$day]['hours'] += $hours;
                    $dailyDuration[$day]['minutes'] += $minutes;
                    $dailyDuration[$day]['seconds'] += $seconds;
                }
            }

            // Format the data for each day
            foreach ($dailyDuration as $day => $duration) {
                if ($duration['seconds'] >= 60) {
                    $duration['minutes'] += floor($duration['seconds'] / 60);
                    $duration['seconds'] %= 60;
                }

                if ($duration['minutes'] >= 60) {
                    $duration['hours'] += floor($duration['minutes'] / 60);
                    $duration['minutes'] %= 60;
                }

                $formattedData[] = [
                    'day' => $day,
                    'hours' => $duration['hours'],
                    'minutes' => $duration['minutes'],
                    'seconds' => $duration['seconds']
                ];
            }

            return response()->json($formattedData);
        }

        if ($request->operation == "hours") {
            $username = $request->username;
            $year = $request->year;
            $month = $request->month;
            $day = $request->day;

            $user = User::with('hyrjeDaljeModel')->where('username', $username)->first();

            if (!$user) {
                return response()->json([]);
            }

            $formattedData = [];

            foreach ($user->hyrjeDaljeModel as $hyrjeDalje) {
                $dateHyrje = new DateTime($hyrjeDalje->data_hyrje);
                $entryDay = $dateHyrje->format('j');

                if ($dateHyrje->format('Y') == $year && $dateHyrje->format('n') == $month && $entryDay == $day) {
                    $formattedData[] = [
                        'entry_time' => $hyrjeDalje->ora_hyrje,
                        'exit_time' => $hyrjeDalje->ora_dalje
                    ];
                }
            }

            return response()->json($formattedData);
        }

    }

}
