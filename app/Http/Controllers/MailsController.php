<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ReportMail;
use Mail;
use App\Models\Report;
class MailsController extends Controller
{   
    public $email;
    public $message;
    public $text;

    /**
     * Send email to email adres
     */
    public function sendEmail(Request $request){
        $this->email = $request->email;
        $this->message = $request->answer;
        $this->text = $request->text;
        Report::where("id",$request->id)->update(["answer" => $this->message]);
        Mail::to($this->email)->send(new ReportMail($this->message,$this->text));
        
        return response()->json([
            "success"=>true
        ],200);
    }
}
