<?php

namespace App\Services;

use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class FcmService
{
    protected $messaging;

    public function __construct()
    {
        $this->messaging = (new Factory)
            ->withServiceAccount(base_path('mymufti-ce4a6-firebase-adminsdk-ljivv-740bba83f5.json'))
            ->createMessaging();
    }

    public function sendNotification($deviceToken, $title, $body, $messageType, $otherData, $notificationType, $questionId = 0, $eventId= 0)
    {

        // Create a notification payload
        $notification = FirebaseNotification::create($title, $body);

        // Create a data payload
        $data = [
            'messageType' => $messageType,
            'other_data' => $otherData,
            'notification_type' => $notificationType,
            'question_id' => $questionId,
            'event_id'    => $eventId,
        ];

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification)
            ->withData($data)
            ->withAndroidConfig([
                'priority' => 'high',
            ])
            ->withApnsConfig([
                'headers' => [
                    'apns-priority' => '10',
                    'apns-push-type' => 'alert',
                ],
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'sound' => 'default',
                        'badge' => 1,
                        'content-available' => 1,
                    ],
                ],
            ]);

        try {
            return $this->messaging->send($message);
        } catch (NotFound $e) {
            // Handle the NotFound exception
            error_log('Entity not found: ' . $deviceToken);
            error_log($e->getMessage());

            // Return a custom response or handle the error as needed
            return response()->json([
                'error' => 'The requested entity was not found.',
                'details' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            // Handle other exceptions
            error_log('An error occurred: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while sending the message.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
