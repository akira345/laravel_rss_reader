<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowLoginHistoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $datas = LoginHistory::query()
            ->where('user_id',$user->id)
            ->orderBy('updated_at','desc')
            ->paginate(25);

        // ビューを返す
        return view('show_history', ['datas' => $datas]);
    }
}
