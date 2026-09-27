<?php

namespace App\Http\Controllers\SMTP;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\EmailService;
use App\Services\Mailgun\MailgunService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MailgunController extends Controller {
    private $apiKey;

    private $emailService;

    public function __construct() {
        $this->emailService = EmailService::where('provider_name', 'mailgun')->first();
        $this->apiKey = $this->emailService?->api_key;
    }

    public function create() {
        return view('smtp.mailgun.verify', [
            'domain' => null,
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'domain_name' => 'required',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors() as $error) {
                notify()->error($error->message);
                return back();
            }
        }

        try {
            // Check if API key exists
            if (!$this->apiKey) {
                notify()->error('Mailgun API key is not configured. Please configure your API key first.');
                return redirect()->back();
            }

            $domain = Domain::create([
                'name' => $request->domain_name,
                'email_service_id' => $request->email_service_id ?? $this->emailService->id,
                'user_id' => auth()->id(),
            ]);
            
            $mg = new MailgunService($this->apiKey);
            $result = $mg->createDomain($request->domain_name);
            
            // Check if the response contains an error message
            if (isset($result->message)) {
                if (str_contains($result->message, 'Invalid private key')) {
                    notify()->error('Invalid Mailgun API key. Please check your API key configuration.');
                    return redirect()->back();
                }
                
                if (str_contains($result->message, 'domain already exists')) {
                    notify()->warning('Domain already exists in Mailgun\'s database!');
                    return back();
                }
            }

            notify()->success('Domain Created Successfully');
            return redirect()->route('mailgun.domain.show', $domain->name);
            
        } catch (\Exception $e) {
            notify()->error('Failed to create domain: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function index() {
        try {
            $apiKey = $this->apiKey;
            
            // Check if API key exists
            if (!$apiKey) {
                notify()->error('Mailgun API key is not configured. Please configure your API key first.');
                return redirect()->back();
            }
            
            $mg = new MailgunService($apiKey);
            $domains = $mg->domains();

            // Check if the response contains an error message
            if (isset($domains->message) && str_contains($domains->message, 'Invalid private key')) {
                notify()->error('Invalid Mailgun API key. Please check your API key configuration.');
                return redirect()->back();
            }

            return view('smtp.mailgun.index', [
                'domains' => $domains->items ?? [],
            ]);
        } catch (\Exception $e) {
            notify()->error('Failed to fetch domains: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function show($domain) {
        try {
            // Check if API key exists
            if (!$this->apiKey) {
                notify()->error('Mailgun API key is not configured. Please configure your API key first.');
                return redirect()->back();
            }

            $mg = new MailgunService($this->apiKey);
            $domainData = $mg->getDomain($domain);
            
            // Check if the response contains an error message
            if (isset($domainData->message) && str_contains($domainData->message, 'Invalid private key')) {
                notify()->error('Invalid Mailgun API key. Please check your API key configuration.');
                return redirect()->back();
            }

            return view('smtp.mailgun.verify', [
                'domain' => $domainData,
            ]);
            
        } catch (\Exception $e) {
            notify()->error('Failed to fetch domain details: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function verify($domain) {
        try {
            // Check if API key exists
            if (!$this->apiKey) {
                notify()->error('Mailgun API key is not configured. Please configure your API key first.');
                return redirect()->back();
            }

            $mg = new MailgunService($this->apiKey);
            $result = $mg->verifyDomain($domain);
            
            // Check if the response contains an error message
            if (isset($result->message)) {
                if (str_contains($result->message, 'Invalid private key')) {
                    notify()->error('Invalid Mailgun API key. Please check your API key configuration.');
                    return redirect()->back();
                }
                
                // Show success message if verification was successful
                notify()->success($result->message);
            }

            // Redirect to domain show page if domain property exists
            if (isset($result->domain->name)) {
                return redirect()->route('mailgun.domain.show', $result->domain->name);
            }
            
            return redirect()->back();
            
        } catch (\Exception $e) {
            notify()->error('Failed to verify domain: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function delete($domain) {
        try {
            // Check if API key exists
            if (!$this->apiKey) {
                notify()->error('Mailgun API key is not configured. Please configure your API key first.');
                return redirect()->back();
            }

            $mg = new MailgunService($this->apiKey);
            $result = $mg->deleteDomain($domain);
            
            // Check if the response contains an error message
            if (isset($result->message)) {
                if (str_contains($result->message, 'Invalid private key')) {
                    notify()->error('Invalid Mailgun API key. Please check your API key configuration.');
                    return redirect()->back();
                }
                
                // Show success message if deletion was successful
                notify()->success($result->message);
            }

            // Redirect to domains index page after deletion
            return redirect()->route('mailgun.domain.index');
            
        } catch (\Exception $e) {
            notify()->error('Failed to delete domain: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
