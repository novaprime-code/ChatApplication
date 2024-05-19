<?php

namespace App\Http\Controllers\API;

use App\Events\VideoCallAccepted;
use App\Events\VideoCallEnded;
use App\Events\VideoCallInitiated;
use App\Events\VideoCallRejected;
use App\Http\Controllers\Controller;
use App\Models\VideoCall;
use Auth;
use Illuminate\Http\Request;

class VideoCallController extends Controller
{

    public function initiateCall(Request $request)
    {
        $validatedData = $request->validate([
            'recipient_id' => 'required|exists:users,id',
        ]);

        $videoCall = VideoCall::create([
            'initiator_id' => Auth::id(),
            'recipient_id' => $validatedData['recipient_id'],
            'status' => 'pending',
        ]);

        event(new VideoCallInitiated($videoCall));

        return response()->json($videoCall);
    }

    public function acceptCall($callId)
    {
        $videoCall = VideoCall::findOrFail($callId);

        if ($videoCall->recipient_id !== Auth::id() || $videoCall->status !== 'pending') {
            return response()->json(['message' => 'Not authorized to accept this call'], 403);
        }

        $videoCall->update([
            'status' => 'accepted',
            'start_time' => now(),
        ]);

        event(new VideoCallAccepted($videoCall));

        return response()->json($videoCall);
    }

    public function rejectCall($callId)
    {
        $videoCall = VideoCall::findOrFail($callId);

        if ($videoCall->recipient_id !== Auth::id() || $videoCall->status !== 'pending') {
            return response()->json(['message' => 'Not authorized to reject this call'], 403);
        }

        $videoCall->update([
            'status' => 'rejected',
        ]);

        event(new VideoCallRejected($videoCall));

        return response()->json($videoCall);
    }

    public function endCall($callId)
    {
        $videoCall = VideoCall::findOrFail($callId);

        if ($videoCall->initiator_id !== Auth::id() && $videoCall->recipient_id !== Auth::id()) {
            return response()->json(['message' => 'Not authorized to end this call'], 403);
        }

        if ($videoCall->status !== 'accepted') {
            return response()->json(['message' => 'Call is not in progress'], 400);
        }

        $videoCall->update([
            'status' => 'ended',
            'end_time' => now(),
        ]);

        event(new VideoCallEnded($videoCall));

        return response()->json($videoCall);
    }

    public function getCallStatus($callId)
    {
        $videoCall = VideoCall::findOrFail($callId);

        if ($videoCall->initiator_id !== Auth::id() && $videoCall->recipient_id !== Auth::id()) {
            return response()->json(['message' => 'Not authorized to view this call'], 403);
        }

        return response()->json($videoCall);
    }
}
