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

    /**
     * @OA\Schema(
     *     schema="VideoCall",
     *     title="Video Call",
     *     required={"id", "initiator_id", "recipient_id", "status"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="initiator_id", type="integer", example=1),
     *     @OA\Property(property="recipient_id", type="integer", example=2),
     *     @OA\Property(property="status", type="string", enum={"pending", "accepted", "rejected", "ended"}, example="pending"),
     *     @OA\Property(property="start_time", type="string", format="date-time"),
     *     @OA\Property(property="end_time", type="string", format="date-time"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time"),
     * )
     */

/**
 * @OA\Post(
 *     path="/api/video-calls",
 *     summary="Initiate a video call",
 *     tags={"Video Calls"},
 *     security={{ "sanctum": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"recipient_id"},
 *             @OA\Property(property="recipient_id", type="integer", description="ID of the recipient user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Video call initiated successfully",
 *     )
 * )
 */

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
/**
 * @OA\Post(
 *     path="/api/video-calls/{callId}/accept",
 *     summary="Accept a video call",
 *     tags={"Video Calls"},
 *     security={{ "sanctum": {} }},
 *     @OA\Parameter(
 *         name="callId",
 *         in="path",
 *         required=true,
 *         description="ID of the video call",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Video call accepted successfully",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="initiator_id", type="integer", example=1),
 *     @OA\Property(property="recipient_id", type="integer", example=2),
 *     @OA\Property(property="status", type="string", enum={"pending", "accepted", "rejected", "ended"}, example="pending"),
 *     @OA\Property(property="start_time", type="string", format="date-time"),
 *     @OA\Property(property="end_time", type="string", format="date-time"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Not authorized to accept this call",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Not authorized to accept this call")
 *         )
 *     )
 * )
 */

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
/**
 * @OA\Post(
 *     path="/api/video-calls/{callId}/reject",
 *     summary="Reject a video call",
 *     tags={"Video Calls"},
 *     security={{ "sanctum": {} }},
 *     @OA\Parameter(
 *         name="callId",
 *         in="path",
 *         required=true,
 *         description="ID of the video call",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Video call rejected successfully",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="initiator_id", type="integer", example=1),
 *     @OA\Property(property="recipient_id", type="integer", example=2),
 *     @OA\Property(property="status", type="string", enum={"pending", "accepted", "rejected", "ended"}, example="pending"),
 *     @OA\Property(property="start_time", type="string", format="date-time"),
 *     @OA\Property(property="end_time", type="string", format="date-time"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Not authorized to reject this call",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Not authorized to reject this call")
 *         )
 *     )
 * )
 */

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
/**
 * @OA\Post(
 *     path="/api/video-calls/{callId}/end",
 *     summary="End a video call",
 *     tags={"Video Calls"},
 *     security={{ "sanctum": {} }},
 *     @OA\Parameter(
 *         name="callId",
 *         in="path",
 *         required=true,
 *         description="ID of the video call",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Video call ended successfully",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="initiator_id", type="integer", example=1),
 *     @OA\Property(property="recipient_id", type="integer", example=2),
 *     @OA\Property(property="status", type="string", enum={"pending", "accepted", "rejected", "ended"}, example="pending"),
 *     @OA\Property(property="start_time", type="string", format="date-time"),
 *     @OA\Property(property="end_time", type="string", format="date-time"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Call is not in progress",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Call is not in progress")
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Not authorized to end this call",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Not authorized to end this call")
 *         )
 *     )
 * )
 */

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
/**
 * @OA\Get(
 *     path="/api/video-calls/{callId}/status",
 *     summary="Get the status of a video call",
 *     tags={"Video Calls"},
 *     security={{ "sanctum": {} }},
 *     @OA\Parameter(
 *         name="callId",
 *         in="path",
 *         required=true,
 *         description="ID of the video call",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Status of the video call retrieved successfully",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="initiator_id", type="integer", example=1),
 *     @OA\Property(property="recipient_id", type="integer", example=2),
 *     @OA\Property(property="status", type="string", enum={"pending", "accepted", "rejected", "ended"}, example="pending"),
 *     @OA\Property(property="start_time", type="string", format="date-time"),
 *     @OA\Property(property="end_time", type="string", format="date-time"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Not authorized to view this call",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Not authorized to view this call")
 *         )
 *     )
 * )
 */

    public function getCallStatus($callId)
    {
        $videoCall = VideoCall::findOrFail($callId);

        if ($videoCall->initiator_id !== Auth::id() && $videoCall->recipient_id !== Auth::id()) {
            return response()->json(['message' => 'Not authorized to view this call'], 403);
        }

        return response()->json($videoCall);
    }
}
