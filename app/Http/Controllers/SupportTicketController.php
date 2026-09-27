<?php

namespace App\Http\Controllers;

use App\Events\NewSupportTicketMail;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Models\User;
use Event;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Notification;
use Session;
use Throwable;
use ZipArchive;

class SupportTicketController extends Controller {
    // All Ticket get here
    public function index() {
        $user_tickets_spesific_user = SupportTicket::all()->unique('ticket_no')->where('user_id', Auth::user()->id);

        $user_tickets = SupportTicket::orderBy('id', 'desc')->with('replies')->get();

        return view('support.index', compact('user_tickets', 'user_tickets', 'user_tickets_spesific_user'));
    }

    // Ticket submit form
    public function submit_request() {
        return view('support.submit-request');
    }

    // Submit the application ticket submit request.
    public function submit_request_submit(Request $request) {
        try {
            $ticket = new SupportTicket;
            $ticket->user_id = Auth::id();
            $ticket->name = $request->name;
            $ticket->email = $request->email;
            $ticket->subject = $request->subject;
            $ticket->phone_number = $request->phone_number;
            $ticket->desc = $request->desc;
            $ticket->mark_as_read = 0;
            $ticket->important = 0;
            $ticket->priority = 'High';
            $ticket->solved = 0;
            $ticket->save();

            $ticket_no = SupportTicket::where('id', $ticket->id)->first();
            $ticket_no->ticket_no = str_pad($ticket->id, 6, '0', STR_PAD_LEFT);
            $ticket_no->save();

            smilify('success', 'Support Ticket Created Successfully');

            return redirect()->route('support.ticket.new');
        } catch (Throwable $th) {
            smilify('error', 'Ooops!! An unexpected error seems to have occured');

            return back();
        }
    }

    // Show the application ticket reply form.
    public function ticket_reply($ticket_no) {
        $user_tickets_spesific_user = SupportTicket::all()->unique('ticket_no')->where('user_id', Auth::user()->id);
        $user_tickets = SupportTicket::orderBy('id', 'desc')->take(5)->get();
        $support_ticket_info = SupportTicket::where('ticket_no', $ticket_no)->with(['user', 'replies'])->first();

        // $replies = SupportTicket::where('ticket_no', $ticket_no)->with(['attachments', 'replies', 'user'])->get();
        return view('support.pages.ticket-reply', compact('support_ticket_info', 'user_tickets_spesific_user', 'user_tickets'));
    }

    // Display a unread listing of the resource.
    public function unread() {
        $user_tickets_spesific_user = SupportTicket::all()->unique('ticket_no')->where('user_id', Auth::user()->id)->where('mark_as_read', 0);
        $user_tickets = SupportTicket::all()->unique('ticket_no')->where('mark_as_read', 0);

        return view('support.pages.unread', compact('user_tickets', 'user_tickets_spesific_user'));
    }

    // Display a starred listing of the resource.
    public function starred() {
        $user_tickets_spesific_user = SupportTicket::all()->unique('ticket_no')->where('user_id', Auth::user()->id)->where('important', 1);
        $user_tickets = SupportTicket::all()->unique('ticket_no')->where('important', 1);

        return view('support.pages.important', compact('user_tickets', 'user_tickets_spesific_user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Frontend\SupportTicket  $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function show($ticket_no) {

        $ticket = SupportTicket::where('ticket_no', $ticket_no)->with(['attachments', 'replies', 'user'])->first();

        if ($ticket->mark_as_read == 0) {
            $mark_as_read = SupportTicket::where('ticket_no', $ticket_no)->update(['mark_as_read' => 1]);
        }

        return view('backend.pages.tickets.show', compact('ticket'));
    }

    public function sent_reply() {

        $user_tickets_spesific_user = SupportTicket::all()->unique('ticket_no')->where('user_id', Auth::user()->id)->where('important', 1);
        $user_tickets = SupportTicket::all()->unique('ticket_no')->where('mark_as_read', 1);

        return view('support.pages.sent', compact('user_tickets', 'user_tickets_spesific_user'));
    }

    // Ticket mark as important requst
    public function mark_star($ticket_no) {
        $important = SupportTicket::where('ticket_no', $ticket_no)->first();

        if ($important->important == 0) {
            $important->update(['important' => 1]);
        } else {
            $important->update(['important' => 0]);
        }

        return back();
    }

    public function mark_as_solved($ticket_no) {
        $solved = SupportTicket::where('id', $ticket_no)->first();

        if ($solved->solved == 0) {
            $solved->update(['solved' => 1, 'solved_by' => Auth::user()->id]);
        } else {
            $solved->update(['solved' => 0, 'solved_by' => Auth::user()->id]);
        }

        return back();
    }

    /**
     * download attachment
     */
    public function download_attachment($ticket_no) {
        $zip = new ZipArchive();
        $fileName = 'downloads/'.$ticket_no.'.zip';
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) == true) {
            $files = File::files(public_path('uploads/tickets/'.$ticket_no));
            foreach ($files as $key => $value) {
                $relativeName = basename($value);
                $zip->addFile($value, $relativeName);
            }
            $zip->close();
        }

        return response()->download(public_path($fileName));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Frontend\SupportTicket  $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupportTicket $supportTicket) {
        //
    }

    // Ticket Search
    public function ticket_search(Request $request) {
        $search = $request->ticket_no;
        $user_tickets = SupportTicket::where('ticket_no', $search)->latest()->paginate(10);
        $user_tickets_spesific_user = SupportTicket::where('ticket_no', $search)->where('user_id', Auth::user()->id)->latest()->paginate(10);

        return view('support.pages.search', compact('user_tickets', 'search', 'user_tickets_spesific_user'));
    }

    /**
     * SUPPORT
     */
    public function ticket() {
        $user_tickets = SupportTicket::where('user_id', Auth::user()->id)
            ->latest()
            ->paginate(10);

        return view('frontend.pages.ticket', compact('user_tickets'));
    }

    /**
     * Show the application ticket.
     *
     * @since v1.0.0
     */
    public function ticket_open() {
        $opens = SupportTicket::where('user_id', Auth::user()->id)
            ->where('solved', 0)
            ->latest()
            ->paginate(10);

        return view('frontend.pages.ticket-open', compact('opens'));
    }

    /**
     * Show the application ticket.
     *
     * @since v1.0.0
     */
    public function ticket_answered() {
        $answers = SupportTicket::where('user_id', Auth::user()->id)
            ->with('replies')
            ->has('replies')
            ->latest()
            ->paginate(10);

        return view('frontend.pages.ticket-answered', compact('answers'));
    }

    /**
     * Show the application ticket.
     *
     * @since v1.0.0
     */
    public function ticket_solved() {
        $user_tickets = SupportTicket::where('solved', 1)
            ->latest()
            ->paginate(10);

        return view('support.pages.solved', compact('user_tickets'));
    }
    // public function submit_request_submit(Request $request)
    // {

    //     $this->validate($request, [
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
    //         'desc' => 'required',
    //         // 'images' => 'mimes:jpg,jpeg,png,gif',
    //     ],[
    //         'name.required' => 'Please enter your name',
    //         'email.required' => 'Please enter your email',
    //         'email.email' => 'Please enter a valid email',
    //         'phone_number.required' => 'Please enter your phone number',
    //         'desc.required' => 'Please enter your description',
    //         // 'images.mimes' => 'Please upload valid file',
    //     ]);

    //     try {

    //         \DB::transaction(function () use ($request) {

    //             $ticket = new SupportTicket;
    //             $ticket->user_id = Auth::id();
    //             $ticket->name = $request->name;
    //             $ticket->email = $request->email;
    //             $ticket->phone_number = $request->phone_number;
    //             $ticket->desc = $request->desc;
    //             $ticket->mark_as_read = 0;
    //             $ticket->important = 0;
    //             $ticket->priority = 'High';
    //             $ticket->solved = 0;
    //             $ticket->save();

    //             $ticket_no = SupportTicket::where('id', $ticket->id)->first();
    //             $ticket_no->ticket_no = genTicket($ticket->id);
    //             $ticket_no->save();

    //             $files = $request->file('images');

    //             if($files != null){

    //                 foreach($files as $file){

    //                     $path=base_path('public/uploads/tickets/' . $ticket_no->ticket_no . '/');
    //                     if (!File::exists($path)) {
    //                         File::makeDirectory($path,0775,true);
    //                     }

    //                     $ogImage = Image::make($file);
    //                     $originalPath = 'public/uploads/tickets/' . $ticket_no->ticket_no . '/';
    //                     $photo_name       =  time().$file->getClientOriginalName();
    //                     $ogImage =  $ogImage->save($originalPath . $photo_name);

    //                     $ticket_attach = new SupportTicketAttachment;
    //                     $ticket_attach->support_ticket_id = $ticket->id;
    //                     $ticket_attach->images = $ticket_no->ticket_no . '/' .$photo_name;
    //                     $ticket_attach->save();

    //                 }
    //             }

    //                 $details = [
    //                 'name' => $request->name,
    //                 'email' => $request->email,
    //                 'phone_number' => $request->phone_number,
    //                 'ticket_no' => $ticket_no->ticket_no,
    //                 'desc' => $request->desc,
    //             ];

    //             session(['details' => $details]);

    //             if ($request->notify_email == 1){
    //                 if (env('EMAIL_NOTIFICATION') == "YES") {
    //                     event(new NewSupportTicketMail($details));
    //                 } //EMAIL_NOTIFICATION
    //             } // $request->notify_email

    //         });

    //         smilify('success', 'Support Ticket Created Successfully');
    //         return redirect()->route('submit.request.success');

    //     } catch (\Throwable $th) {
    //         smilify('error', 'Ooops!! An unexpected error seems to have occured');
    //         return back();
    //     }
    // }

}
