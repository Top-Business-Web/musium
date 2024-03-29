<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\GroupColor;
use App\Models\GroupCustomer;
use App\Models\GroupMovement;
use App\Models\Groups;
use App\Models\RouteGroup;
use App\Models\Supervisor;
use App\Models\SupervisorActivity;
use App\Models\SupervisorLog;
use Carbon\Carbon;
use Couchbase\Group;
use Exception;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Composer\Autoload\includeFile;

class SupervisorController extends Controller
{
    public function index()
    {

        $activities = Activity::with(['groups'])->orderBy('id', 'asc')->get();
        $activities_test = SupervisorActivity::whereDate('date_time', Carbon::now()->format('Y-m-d'))
            ->groupBy('activity_id')->get();

//        return $activities_test;

        $group_customers_waiting = GroupColor::groupNotColored()->get();

        $group_customer_join = GroupCustomer::whereDate('date_time', Carbon::now()->format('Y-m-d'))
            ->groupBy('group_id')->get();

        $supervisor_activities = SupervisorActivity::where('supervisor_id', auth()->user()->id)
            ->whereDate('date_time', Carbon::now()->format('Y-m-d'))->first();

//        $group_colors_active = GroupColor::groupColored()->get();

//        return $group_customers_waiting;
//        return $supervisor_activities;


        return view('platform.activities.index', compact('group_customers_waiting', 'activities', 'group_customer_join', 'supervisor_activities', 'activities_test'));
    }

    public function joinActivaties()
    {
        $activities = Activity::get();
        $supervisor_activities = SupervisorActivity::where('supervisor_id', auth()->user()->id)
            ->whereDate('date_time', Carbon::now()->format('Y-m-d'))->get();
        return view('platform.activities.join_activaties', compact('activities', 'supervisor_activities'));
    }

    public function addActivity(Request $request)
    {
        SupervisorActivity::create([
            'date_time' => Carbon::now()->format('Y-m-d H:i:s'),
            'status' => 'available',
            'activity_id' => $request->activity,
            'supervisor_id' => $request->supervisor,
        ]);

        return redirect()->route('platform');
    }

    public function requestsActivity()
    {
        return view('platform.Accept_groups.index');
    }

    public function showRequest()
    {
        $groupMovment = GroupMovement::where('accept', '=', 'waiting')->where('supervisor_accept_id', auth('admin')->id())->get();
        return view('platform.Accept_groups.index', compact('groupMovment'));
    } // end show

    public function groupAccept(Request $request)
    {
        $accept = 'accept';
        $group = GroupMovement::find($request->id);

        $group->update([
            'accept' => $accept,
        ]);

        $super = SupervisorActivity::where('supervisor_id', '=', auth('admin')->user()->id)
            ->whereDate('date_time', Carbon::now()->format('Y-m-d'))
            ->update(['status' => 'not_available']);
        if ($super) {
            SupervisorLog::created([
                'name' => auth('admin')->user()->name,
                'status' => 'not_available',
            ]);
        }

        if ($super) {
            return redirect()->back()->with('success', 'Group Accepted');
        }

        return response()->json('error');

    } // end groupAccept

    public function groupNotAccept(Request $request)
    {

        $not_accept = 'not_accept';


        $groupRequest = GroupMovement::find($request->id);


        $checkLastActivity = GroupMovement::where('status', '=', 'out')
            ->where('accept', '!=','not_accept')
            ->where('group_id', '=', $groupRequest->group_id)
            ->whereDate('date_time', Carbon::now()->format('Y-m-d'))
            ->latest()->first();

//        return $checkLastActivity;

        if ($checkLastActivity) {
            $checkLastActivity->update([
                'status' => 'in'
            ]);

            $groupRequest->update([
                'accept' => $not_accept,
                'status' => 'out',
            ]);


            SupervisorLog::created([
                'name' => auth('admin')->user()->name,
                'status' => 'not_available',
            ]);
            return redirect()->back()->with('success', 'Not Accept Successfully');

        } else {

            $groupRequest->update([
                'accept' => $not_accept,
                'status' => 'out',
            ]);

            GroupCustomer::where('group_id', $groupRequest->group_id)->update(['status' => 'waiting']);
            GroupColor::where('group_id', $groupRequest->group_id)->update(['color' => null]);

            return redirect()->back()->with('success', 'Not Accept Successfully');
        }


    } // end groupNotAccept

    public function getLastRequests()
    {
        $groupMovment = GroupMovement::where('accept', '=', 'waiting')
            ->where('supervisor_accept_id', auth('admin')->id())
            ->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->get();
        if ($groupMovment->count() == 0) {

            return response()->json(false);
        }
        return response()->json(true);
    } // end getLastReq

    public function activityBreak()
    {

        $user = SupervisorActivity::where('supervisor_id', auth('admin')->user()->id)
            ->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->first();

        ($user->status == 'available') ? $user->status = 'break' : $user->status = 'available';
        $user->save();

        $logs = SupervisorLog::create([
            'name' => $user->supervisors->name,
            'status' => $user->status,
        ]);

        if ($user->status == 'available') {
            return redirect()->back()->with('success', 'You are Available From Now !');
        } else {
            return redirect()->back()->with('success', 'Your Are in Break Now !');
        }

    } // end activityBreak

    public function groupMoves()
    {
        $groups = GroupMovement::whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->orderBy('created_at', 'DESC')->get();

        return view('platform.activities.group_moves', compact('groups'));
    } // end groupMoves

    public function supervisorMoving()
    {
        $supervisor = SupervisorLog::orderByDesc('created_at')->get();
        return view('platform.activities.supervisor_moves', compact('supervisor'));
    } // end supervisorMoving


    /**
     * @throws Exception
     */
    public function resetSupervisorActivity()
    {
        $super_setup = SupervisorActivity::where('supervisor_id', auth()->user()->id)
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))->first();

        $activity_setup = SupervisorActivity::where('activity_id', $super_setup->activity_id)
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->count('activity_id');

        $groups = GroupMovement::whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->where('supervisor_accept_id', auth()->user()->id)
            ->where('activity_id', $super_setup->activity_id)
            ->where('accept', 'accept')
            ->get();

        if ($activity_setup > 1 && $groups->count() < 1) {
            SupervisorActivity::whereDate('created_at', Carbon::now()->format('Y-m-d'))
                ->where('supervisor_id', auth('admin')->user()->id)
                ->delete();

            return redirect()->route('platform')->with('success', 'you are leave activity');
        } else {
            return redirect()->route('platform')->with('success', 'you are not have this permission now');
        } // end if

    } // end resetSupervisorActivity

    public function listSupervisors(Request $request)
    {
        $supervisors = SupervisorActivity::where('activity_id', $request->activity_id)->get();

        return $supervisors;
    }


} // end controller
