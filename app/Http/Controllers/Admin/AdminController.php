<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HealthCenter;
use App\Models\Notification;
use App\Models\Company;
use App\Models\Country;
use App\Models\Question;
use App\Models\User;
use App\Models\News;
use App\Models\Checkin;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $allowedCountries  = config('allowedcountries.codes');
        $users = User::all()->count();
        $curWkUsr = $this->getCurrentWeekRecords('User');

        $healthCenters = HealthCenter::all()->count();
        $curWkHC  = $this->getCurrentWeekRecords('HealthCenter'); 

        $companies  = Company::all()->count();
        $curWkComp  = $this->getCurrentWeekRecords('Company');

        $countries   = Country::where('switch',1)->count();
        $curWkCount  = $this->getCurrentWeekRecords('Country');

        $questions  = Question::all()->count();
        $curWkQues  = $this->getCurrentWeekRecords('Question');

        $notifications = Notification::all()->count();
        $curWkNotify   = $this->getCurrentWeekRecords('Notification');

        $checkins = Checkin::all()->count();
        $curWkcheckin   = $this->getCurrentWeekRecords('Checkin');

        $recentNews = News::latest()->limit(20)->get();
        return view('admin.home.home', compact('users','curWkUsr', 'healthCenters', 'curWkHC', 'companies', 'curWkComp', 'countries', 'curWkCount', 'questions', 'curWkQues', 'notifications', 'curWkNotify', 'recentNews','checkins','curWkcheckin'));
    }

    public function getCurrentWeekRecords($model) {
        $now = \Carbon\Carbon::now();
        $weekStart = $now->subDays($now->dayOfWeek)->setTime(0, 0);
        $modelPath = "\App\Models".'\\'.$model;
        return $modelPath::where('created_at', '>=', $weekStart)->count();
    }
}
