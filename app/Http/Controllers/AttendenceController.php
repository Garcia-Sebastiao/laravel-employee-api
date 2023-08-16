<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendenceController extends Controller
{
    public function clock_in(Request $request)
    {
        $user_id = $request->user()->id;

        $attendence = Attendence::where('date', Carbon::today())->where('user_id', $user_id)->first();

        if (!$attendence) {
            $attendence = Attendence::create(
                [
                    'user_id' => $user_id,
                    'date' => Carbon::today()
                ]
            );
        }

        if (!$attendence->clock_in) {
            $attendence->clock_in = Carbon::now();
            $attendence->save();
        }

        return response()->json($attendence, 200);
    }

    public function clock_out(Request $request)
    {
        $user_id = $request->user()->id;

        $attendence = Attendence::where('date', Carbon::today())->where('user_id', $user_id)->first();

        if (!$attendence) {
            $attendence = Attendence::create(
                [
                    'user_id' => $user_id,
                    'date' => Carbon::today()
                ]
            );
        }

        $attendence->clock_out = Carbon::now();
        $attendence->save();

        return response()->json($attendence, 200);
    }

    public function reports(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'start' => 'required|date',
                'end' => 'required|date'
            ]
        );

        $attendence = Attendence::where('user_id', $id)->whereBetween('date', [
            $validated['start'],
            $validated['end']
        ])->orderBy('date', 'asc')->get();

        return response()->json($attendence, 200);
    }

    public function all_reports(Request $request)
    {
        $validated = $request->validate(
            [
                'start' => 'required|date',
                'end' => 'required|date'
            ]
        );
    }
}