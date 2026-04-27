<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminEmailService
{
    public static function send(string $type, $admin, array $extra = []): void
    {
        $subjects = [
            'verify'         => 'Verify Your Email Address',
            'approved'       => 'Your Account Has Been Approved',
            'rejected'       => 'Your Account Registration Update',
            'suspended'      => 'Your Account Has Been Suspended',
            'reset_password' => 'Reset Your Password',
        ];

        $data = array_merge([
            'name'  => $admin->name,
            'email' => $admin->email,
            'id'    => $admin->id,
        ], $extra);

        try {
            Mail::send("emails.admin.{$type}", $data, function ($message) use ($admin, $subjects, $type) {
                $message->to($admin->email)
                    ->subject($subjects[$type] ?? config('app.name') . ' Notification');
            });
        } catch (\Exception $e) {
            Log::error('Email failed: ' . $e->getMessage(), [
                'type' => $type,
                'to'   => $admin->email,
            ]);
        }
    }
}
