<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller {
    /**
     * Display a listing of the resource.
     * contact controller
     *
     * @return \Illuminate\Contracts\View\Factory|Illuminate\Contracts\View\View
     */
    public function index() {
        $contacts = Contact::latest()->paginate();

        return view('contact.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('frontend.argon.contact.contact-us');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);
        Contact::create([
            'full_name' => $request->name,
            'email' => $request->email,
            'company' => $request->company,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        notify()->success('Message Sent!');

        return back()->with('message', 'Message Sent!');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact) {
        $contact->delete();

        return back();
    }

    public function replay($contact) {
        $contact = Contact::where('id', $contact)->first();

        return view('contact.reply', compact('contact'));
    }

    public function show($contact) {
        $contact = Contact::where('id', $contact)->first();

        return view('contact.reply', compact('contact'));
    }

    public function replaySent(Request $request, $contact) {
        $contactInfo = Contact::where('id', $contact)->first();
        $contactInfo->reply = $request->body;
        $contactInfo->update();

        $arrayEmails = $contactInfo->email;
        $emailSubject = $contactInfo->subject;
        $data = [
            'name' => $contactInfo->full_name,
            'email' => $contactInfo->email,
            'subject' => $contactInfo->subject,
            'body' => $request->body,
        ];

        Mail::send('contact.mail', $data, function ($message) use ($arrayEmails, $emailSubject) {
            $message->to($arrayEmails)
                ->subject($emailSubject);
        }
        );

        return back();

    }
}
