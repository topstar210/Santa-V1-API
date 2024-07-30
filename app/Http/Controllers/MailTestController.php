<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailTestController extends Controller
{
    public function sendTestEmail()
    {
        $details = [
            'title' => 'Test Email from Laravel',
            'body' => 'This is a test email to verify SMTP settings.'
        ];

        Mail::raw($details['body'], function ($message) use ($details) {
            $message->to('topstar20210@gmail.com') // Replace with your email
                    ->subject($details['title']);
        });

        return 'Email has been sent';
    }
}
