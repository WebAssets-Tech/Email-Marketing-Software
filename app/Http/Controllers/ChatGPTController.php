<?php

namespace App\Http\Controllers;

use App\Models\ChatGPTMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Throwable;

class ChatGPTController extends Controller
{
    public function chatgpt()
    {
        return view('chatgpt.index');
    }

    public function single(Request $request, $parent_id)
    {
        $singleMessages = ChatGPTMessage::where('parent_id', $parent_id)->get();

        return view('chatgpt.single', compact('singleMessages', 'parent_id'));
    }

    public function chat(Request $request)
    {
        try {
            $prompt = $request->input('prompt');
            $parent_id = $request->input('parent_id') ?? auth()->id();

            $response = Http::gpt($prompt);

            ChatGPTMessage::create([
                'parent_id' => $parent_id,
                'question' => $prompt,
                'reply' => $response->json()['choices'][0]['message']['content'],
            ]);

            return $response->json()['choices'][0]['message']['content'];
        } catch (Throwable $th) {
            return $th;
        }
    }

    public function chatStore(Request $request)
    {
        Artisan::call('optimize:clear');
        $prompt = $request->input('prompt');
        try {
            $response = Http::gpt($prompt);
            $chatgptmessageMain = ChatGPTMessage::create([
                'question' => $prompt,
                'reply' => $response->json()['choices'][0]['message']['content'],
            ]);

            $chatgptmessageMain->update([
                'parent_id' => $chatgptmessageMain->id,
                'parent' => true,
            ]);

            return redirect()->route('chat.gpt.single', $chatgptmessageMain->id);
        } catch (Throwable $th) {
            notify()->error($th->getMessage());

            return back();
        }
    }

    public function chatFloating(Request $request)
    {
        try {
            $prompt = $request->input('prompt');
            $response = Http::gpt($prompt);

            return $response->json()['choices'][0]['message']['content'];
        } catch (Throwable $th) {
            notify()->error($th->getMessage());

            return view('chatgpt.error', ['message' => $th->getMessage()]);
            // return response()->json(['error' => $th->getMessage()]);
        }
    }

    public function chatgptSetup()
    {
        return view('chatgpt.setup');
    }

    public function chatgptSetupUpdate(Request $request)
    {
        createOrOverwriteChatGptApiKey($request->CHATGPT_API_KEY);
        notify()->success(translate('ChatGPT Api key updated successfully'));
        Artisan::call('config:cache');
        Artisan::call('config:clear');

        return back();
    }
}
