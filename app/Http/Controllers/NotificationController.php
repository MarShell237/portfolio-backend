<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\NotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Notifications\ContactNotification;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class NotificationController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function index()
    {
        $notifications = $this->userRepository
                                ->connected()
                                ->notifications()
                                ->select('id')
                                ->latest('id')
                                ->paginate()
                                ->toArray();

        return ApiResponse::ok(
            "success to get user notification",
            $notifications
        );
    }


    public function show($notification_id)
    {
        try{
            $notification = $this->userRepository->connected()->notifications()->where('id', $notification_id)->first();

            if (!$notification) {
                abort(Response::HTTP_NOT_FOUND,"notification not found");
            }

            return ApiResponse::ok(
                "success to get notification",
                ['notification' => new NotificationResource($notification)]
            );
        } catch (Throwable $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }

    public function store(NotificationRequest $request){
        $data = $request->validated();
        $admin = User::where('email', config('admin.email'))->firstOrFail();
        $user = $this->userRepository->connected();
        $fileUrl = null;
        $path= null;
        if ($request->hasFile('attachment')) {
            $filename = uniqid() . '__' . $request->file('attachment')->getClientOriginalName() ;
            $path = $request->file('attachment')->storeAs('attachments', $filename, 'public');
            $fileUrl = Storage::disk('public')->url($path);
        }

        $admin->notify(new ContactNotification(
            name: $user->name,
            email: $user->email,
            object: $data['object'],
            message: $data['message'],
            fileUrl: $fileUrl,
            filePath: $path
        ));

        return ApiResponse::ok(
            'Your message has been successfully sent to admin.'
        );
    }

    public function markAllAsRead()
    {
        $this->userRepository->connected()->unreadNotifications->markAsRead();

        return ApiResponse::ok(
            "success to mark all notifications as read",
        );
    }

    public function markAsRead($notification_id)
    {
        try{
            $notification = $this->userRepository->connected()->notifications()->where('id', $notification_id)->first();

            if (!$notification) {
                abort(Response::HTTP_NOT_FOUND,"notification not found");
            }

            $notification->markAsRead();

            return ApiResponse::ok(
                "success to mark notification as read",
            );
        } catch (Throwable $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($notification_id)
    {
        try{
            $notification = $this->userRepository->connected()->notifications()->where('id', $notification_id)->first();

            if (!$notification) {
                abort(Response::HTTP_NOT_FOUND,"notification not found");
            }

            $notification->delete();

            return ApiResponse::ok(
                "success to delete notification",
            );
        } catch (Throwable $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }

    public function clearRead()
    {
        $this->userRepository->connected()->readNotifications()->delete();

        return ApiResponse::ok(
            "success to clear read notifications",
        );
    }

    public function unreadCount()
    {
        $unreadCount = $this->userRepository->connected()->unreadNotifications()->count();

        return ApiResponse::ok(
            "success to get unread notifications count",
            ['data' => $unreadCount]
        );
    }
}
