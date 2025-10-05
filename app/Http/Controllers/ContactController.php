<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'txt_contactname'    => 'required|string|max:100',
            'txt_contactemail'   => 'required|email',
            'txt_contactsubject' => 'required|string|max:150',
            'txt_contactphone'   => 'nullable|string|max:20',
            'txt_contactmessage' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }
    
        // ✅ Map to a cleaner array for the email view
        $data = [
            'name'    => $request->input('txt_contactname'),
            'email'   => $request->input('txt_contactemail'),
            'subject' => $request->input('txt_contactsubject'),
            'phone'   => $request->input('txt_contactphone'),
            'body' => $request->input('txt_contactmessage'),
        ];
    
        // ✅ Send email
       $resp= Mail::send('email.contactformat', $data, function ($message) use ($data) {
            $message->to('info@desikitchen-ky.com')
                    ->from('info@desikitchen-ky.com', 'Desi Kitchen Contact Form')
                    ->subject('Contact: '.$data['subject']);
        });
        


        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your message has been sent.'
        ]);
    }
}
