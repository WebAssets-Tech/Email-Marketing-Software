<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\EmailGroup;
use App\Utils\CSV as MYCSV;
use Illuminate\Support\Str;
use App\Models\EmailContact;
use Illuminate\Http\Request;
use App\Models\CampaignEmail;
use App\Models\EmailListGroup;
use Illuminate\Support\Facades\DB;
use App\Exports\EmailContactExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use RealRashid\SweetAlert\Facades\Alert;

class EmailContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('email_contacts.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $request->validate([
            'name' => 'required',
        ]);

        try {
            $email = new EmailContact();
            $email->owner_id = userId();
            $email->name = $request->name;
            $email->email = $request->email;
            $email->country_code = $request->country_code;
            $email->phone = $request->phone;
            $email->save();

            Alert::success(translate('Success'), translate('New Email Contact Stored'));

            return back();
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * SHOW
     */
    public function show($id)
    {
        try {
            $email = EmailContact::where('id', $id)->first();

            if ($email != null) {
                return view('email_contacts.show', compact('email'));
            } else {
                Alert::error(translate('Whoops'), translate('No Email Found'));

                return back();
            }
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $request->validate([
            'name' => 'required',
        ]);

        try {
            $email_update = EmailContact::where('id', $id)->first();

            if ($email_update != null) {
                $email_update->owner_id = userId();
                $email_update->name = $request->name;
                $email_update->email = $request->email;
                $email_update->country_code = $request->country_code;
                $email_update->phone = $request->phone;
                $email_update->save();
                Alert::success(translate('Success'), translate('Information Updated'));

                return back();
            } else {
                Alert::error(translate('Whoops'), translate('Something went wrong. Check user exist first.'));

                return back();
            }
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * EMAILS
     */
    public function emails()
    {
        $emails = EmailContact::HasAgent()
            ->orderBy('email')
            ->Active()
            ->latest()
            ->simplePaginate(20);

        return view('email_contacts.load_pages.emails', compact('emails'));
    }

    /**
     * EMAIL LIST
     */
    public function emailList()
    {
        return view('email_contacts.list.email');
    }

    /**
     * PHONE LIST
     */
    public function phoneLIst()
    {
        return view('email_contacts.list.phone');
    }

    /**
     * favourites
     */
    public function favourite()
    {
        $favourites = EmailContact::HasAgent()->orderBy('email')->Favourite()->latest()->get();

        return view('email_contacts.load_pages.favourites', compact('favourites'));
    }

    /**
     * blocked
     */
    public function blocked()
    {
        $blocks = EmailContact::HasAgent()->orderBy('email')->Blocked()->latest()->get();

        return view('email_contacts.load_pages.blocked', compact('blocks'));
    }

    /**
     * unblockAll
     */
    public function unblockAll(Request $request)
    {
        $ids = $request->ids;
        $trashing = EmailContact::whereIn('id', explode(',', $ids))->update(['blocked' => 0]);

        return response()->json(['status' => true, 'message' => translate('Email contact unblocked successfully.')]);
    }

    /**
     * trashed
     */
    public function trashedBin()
    {
        $trashes = EmailContact::HasAgent()->TrashedBin()->latest()->get();

        return view('email_contacts.load_pages.trashed', compact('trashes'));
    }

    /**
     * destroyAll
     */
    public function destroyAll(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }
        $ids = $request->ids;
        // $trashing = EmailContact::whereIn('id', explode(',', $ids))->update(['trashed' => 1]);
        EmailContact::whereIn('id', explode(',', $ids))->delete();

        return response()->json(['status' => true, 'message' => translate('Email contact deleted successfully.')]);
    }

    public function destroyAllContact(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }
        if ($request->check_all_emails) {
            $contacts = DB::table('email_contacts')->where('owner_id', auth()->user()->id)->delete();
            // $contacts = EmailContact::where('owner_id', auth()->user()->id)->delete();
            Alert::success('success', 'All Contacts Deleted');

            return back();
        }
    }

    /**
     * destroy
     */
    public function destroy($id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        EmailContact::where('id', $id)->delete();

        $checkID = CampaignEmail::where('email_id', $id)->first();

        if ($checkID != null) {
            CampaignEmail::where('email_id', $id)->delete();
        }

        Alert::success(translate('Deleted'), translate('Contact Deleted'));

        return back();
    }

    /**
     * restoreAll
     */
    public function restoreAll(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $ids = $request->ids;
        EmailContact::whereIn('id', explode(',', $ids))->restore();
        $trashing = EmailContact::whereIn('id', explode(',', $ids))->update(['trashed' => 0]);

        return response()->json(['status' => true, 'message' => translate('Email contact restored successfully.')]);
    }

    /**
     * destroy
     */
    public function permanentDestroyAll(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $ids = $request->ids;
        EmailContact::whereIn('id', explode(',', $ids))->forceDelete();

        return response()->json(['status' => true, 'message' => translate('Email contact destroyed successfully.')]);
    }

    /**
     * blacklistAll
     */
    public function blacklistAll(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $ids = $request->ids;
        $trashing = EmailContact::whereIn('id', explode(',', $ids))->update(['blocked' => 1]);

        return response()->json(['status' => true, 'message' => translate('Email contact blacklisted successfully.')]);
    }

    /**
     * favouriteAll
     */
    public function favouriteAll(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $ids = $request->ids;
        $trashing = EmailContact::whereIn('id', explode(',', $ids))->update(['favourites' => 1]);

        return response()->json(['status' => true, 'message' => translate('Email contact added to favourites successfully.')]);
    }

    /**
     * dislikeAll
     */
    public function dislikeAll(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $ids = $request->ids;
        $trashing = EmailContact::whereIn('id', explode(',', $ids))->update(['favourites' => 0]);

        return response()->json(['status' => true, 'message' => translate('Email contact removed from favourites successfully.')]);
    }

    /**
     * mailSearch
     */
    public function mailSearch(Request $request)
    {
        $emails = EmailContact::where('email', 'LIKE', '%' . $request->value . '%')
            ->orWhere('name', 'LIKE', '%' . $request->value . '%')
            ->orderBy('email')->get();
        $sendSearch = '';
        foreach ($emails as $email) {
            $sendSearch .= '<tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-6">
                                        <div class="inline-flex w-10 h-10">
                                            <img class="w-10 h-10 object-cover rounded-full"
                                                alt="' . $email->email . '"
                                                src="' . emailAvatar($email->email) . '" />
                                        </div>
                                        <div>
                                            <p>
                                                <label for="261">' . $email->name . '</label>
                                            </p>
                                            <p class="text-gray-500 text-sm font-semibold">
                                                <label for="261">' . $email->email . '</label>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">' . $email->created_at->diffForHumans() . '</td>
                                <td class="py-4 text-right">
                                    <div class="flex-none flex justify-end mr-4">
                                        <a href="' . route('email.contact.destroy', $email->id) . '"
                                        hx-confirm="Are you sure?" hx-target="closest tr" hx-swap="delete"
                                        class="cursor-pointer w-5 h-5 flex-none ml-4 flex items-center justify-center text-gray-500"
                                        title="Delete selected email">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                <line x1="10" y1="11" x2="10" y2="17"/>
                                                <line x1="14" y1="11" x2="14" y2="17"/>
                                            </svg>
                                        </a>
                                        <a href="' . route('email.contact.show', $email->id) . '"
                                        class="w-5 h-5 flex-none ml-4 flex items-center justify-center text-gray-500 tooltip"
                                        title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>';
        }
        return $sendSearch;
    }

    /**
     * sendEmail
     */
    public function sendEmail(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $ids = $request->ids;
        $emails = EmailContact::whereIn('email', explode(',', $ids))
            ->get()
            ->pluck('email')
            ->toArray();

        foreach ($emails as $email) {
            Artisan::call('mail:send-test SendTestMail ' . $email); // test mail
        }

        return response()->json(['status' => true, 'message' => translate('Test mail sent successfully.')]);
    }

    /**
     * EXPORT
     */
    public function emailExport()
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        try {
            return Excel::download(new EmailContactExport, 'users.csv');
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * markAsRead
     */
    public function markAsRead(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $markAsRead = EmailContact::where('id', $request->id)->first();

        if ($markAsRead->favourites == 1) {
            $markAsRead->favourites = 0;
        } else {
            $markAsRead->favourites = 1;
        }

        $markAsRead->save();

        return response()->json('success', 200);
    }

    /**
     * bulk csv
     */
    public function bulkCsv()
    {
        return view('bulk.index');
    }

    public function importCsv(Request $request)
    {
        // return $request->all();
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        try {
            $request->validate([
                'csv' => 'required|max:20000|mimes:csv',
            ], [
                'csv.required' => 'Upload file is required',
                'csv.max' => 'File size must be smaller then 20MB',
                'csv.mimes' => 'File must be csv',
            ]);

            if (File::exists(public_path('uploads/csv/' . Auth::user()->id . '.csv'))) {
                File::delete(public_path('uploads/csv/' . Auth::user()->id . '.csv'));
            }

            if ($request->hasFile('csv')) {
                // $imageName = Auth::user()->id . '.' . $request->csv->getClientOriginalExtension();
                // $request->csv->move(public_path('/uploads/csv'), $imageName);
                // $file = asset('uploads/csv/' . Auth::user()->id . '.csv');
                $csv = new MYCSV();
                $csv->upload($request->csv);
                $contacts = $csv->parse();
                // $contacts = convert_csv_to_json($file);

                if ($request->isGroup && $request->name != null) {
                    // create a new group
                    $group = new EmailGroup();
                    $group->name = $request->name;
                    $group->description = $request->description ?? '<p>contacts</p>';
                    $group->owner_id = Auth::user()->id;
                    $group->status = true;
                    $group->type = $request->type;
                    $group->save();
                }

                foreach ($contacts as $value) {
                    if (! empty($value['email'])) {
                        $email = new EmailContact;
                        $email->owner_id = Auth::user()->id;
                        $email->name = $value['name'] ?? null;
                        $email->email = $value['email'];
                        $email->country_code = $value['country_code'] ?? null;
                        $email->phone = $value['phone'] ?? null;
                        $email->favourites = $value['favourites'] ?? 0;
                        $email->blocked = $value['blocked'] ?? 0;
                        $email->trashed = $value['trashed'] ?? 0;
                        $email->is_subscribed = $value['is_subscribed'] ?? 0;
                        $email->save();

                        if ($request->isGroup && $request->name != null) {
                            // store EmailContact into EmailListGroup
                            $campaign_email = new EmailListGroup();
                            $campaign_email->email_group_id = $group->id;
                            $campaign_email->email_id = $email->id;
                            $campaign_email->owner_id = Auth::user()->id;
                            $campaign_email->save();
                        }
                    }
                }
            }

            Alert::success(translate('Success'), translate('CSV Imported'));

            return back();
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * EXPORT
     */
    public function exportCsv()
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        try {
            $connect = mysqli_connect(
                env('DB_HOST'),
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                env('DB_DATABASE')
            );

            header('Content-Type: text/csv; charset=utf-8');

            header('Content-Disposition: attachment; filename=data.csv');

            $output = fopen('php://output', 'w');

            fputcsv(
                $output,
                [
                    'id',
                    'owner_id',
                    'name',
                    'email',
                    'country_code',
                    'phone',
                    'favourites',
                    'blocked',
                    'trashed',
                    'is_subscribed',
                    'deleted_at',
                    'created_at',
                    'updated_at',
                ]
            );
            $owner_id = auth()->id();

            $query = "SELECT * from email_contacts where owner_id='{$owner_id}' ORDER BY id DESC";

            $result = mysqli_query($connect, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                fputcsv($output, $row);
            }

            fclose($output);
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * DOWNLOAD CSV
     */
    public function sampleCsv()
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        try {
            return response()->download(csv_path());
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Invoice Not Found'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * AJAX PAGINATION
     */
    public function fetch_data(Request $request)
    {
        if ($request->ajax()) {
            $emails = EmailContact::HasAgent()
                ->whereNotNull('email')
                ->orderBy('email')
                ->Active()
                ->latest()
                ->simplePaginate(20);

            return view('email_contacts.load_pages.emails', compact('emails'));
        }
    }

    //END
}
