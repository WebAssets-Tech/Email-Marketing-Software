<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Auth;
use DB;
use Illuminate\Http\Request;
use Throwable;

class SupportTicketReplyController extends Controller {
    public function index(Request $request, $id, $ticket_no) {
        $this->validate($request, [
            'reply' => 'required',
        ], [
            'reply.required' => 'Reply can not be empty',
        ]);

        try {

            DB::transaction(function () use ($request, $id, $ticket_no) {

                $reply = new SupportTicketReply;
                $reply->support_ticket_id = $id;
                $reply->ticket_no = $ticket_no;
                $reply->reply = $request->reply;
                $reply->reply_by = Auth::user()->id;
                $reply->save();
                if (Auth::user()->user_type == 'Admin') {
                    SupportTicket::where('ticket_no', $ticket_no)->update(['mark_as_read' => 1]);
                } else {
                    SupportTicket::where('ticket_no', $ticket_no)->update(['mark_as_read' => 0]);
                }
            });

            smilify('success', 'Replied Successfully');

            return back();
        } catch (Throwable $th) {
            smilify('error', 'Ooops!! An unexpected error seems to have occured');

            return back();
        }
    }

    //END
}
