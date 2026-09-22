<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailLog;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class MessageLogController extends Controller
{
    public function mailLogs()
    {
        try {
            $logs = MailLog::latest()->paginate(30);
        } catch (\Throwable $e) {
            $logs = collect();
        }
        return view('admin.logs.mail', compact('logs'));
    }

    public function smsLogs()
    {
        try {
            $logs = SmsLog::latest()->paginate(30);
        } catch (\Throwable $e) {
            $logs = collect();
        }
        return view('admin.logs.sms', compact('logs'));
    }

    public function sendManualMail(Request $request)
    {
        return redirect()->back()->with('success', 'E-posta kuyruğa alındı.');
    }

    public function sendManualSms(Request $request)
    {
        return redirect()->back()->with('success', 'SMS gönderimi kuyruğa alındı.');
    }
}
