<?php

namespace App\Http\Controllers;

use App\Models\AdminNotice;
use Illuminate\Http\Request;

class UserNoticeController extends Controller
{
    /**
     * Mark one of the current user's admin notices as read/dismissed.
     */
    public function markRead(Request $request, AdminNotice $notice)
    {
        abort_unless($notice->user_id === $request->user()->id, 403);

        if (!$notice->isRead()) {
            $notice->update(['read_at' => now()]);
        }

        return back();
    }
}
