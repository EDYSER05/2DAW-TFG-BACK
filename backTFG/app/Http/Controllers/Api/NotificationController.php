<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Http\Requests\StoreNotificationRequest;
use Exception;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Notification::query()->orderBy('created_at', 'desc');

            // ?user_id
            if ($request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            // ?is_read
            if ($request->has('is_read')) {
                $query->where('is_read', $request->boolean('is_read'));
            }

            return NotificationResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener las notificaciones', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Notification $notification)
    {
        try {
            return new NotificationResource($notification);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener la notificación', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreNotificationRequest $request)
    {
        try {
            $data = $request->validated();

            $notification = Notification::create($data);

            return (new NotificationResource($notification))
                ->additional(['msg' => 'Notificación creada correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear la notificación', 'error' => $e->getMessage()], 500);
        }
    }

    public function markAsRead(Notification $notification)
    {
        try {
            $notification->update(['is_read' => true]);

            return (new NotificationResource($notification))->additional(['msg' => 'Notificación marcada como leída']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al marcar la notificación', 'error' => $e->getMessage()], 500);
        }
    }

    public function markAllRead(Request $request)
    {
        try {
            $data = $request->validate(['user_id' => 'required|exists:users,id']);

            Notification::where('user_id', $data['user_id'])->where('is_read', false)->update(['is_read' => true]);

            return response()->json(['msg' => 'Todas las notificaciones marcadas como leídas']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al marcar las notificaciones', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Notification $notification)
    {
        try {
            $notification->delete();

            return response()->json(['msg' => 'Notificación eliminada correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al eliminar la notificación', 'error' => $e->getMessage()], 500);
        }
    }
}
