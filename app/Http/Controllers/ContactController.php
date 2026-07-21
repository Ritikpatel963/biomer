<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150'],
            'phone' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
        ];

        if (filled(config('services.recaptcha.secret_key'))) {
            $rules['g-recaptcha-response'] = ['required', 'string'];
        }

        $validated = $request->validate($rules, [
            'phone.regex' => 'Please enter a valid phone number.',
            'message.min' => 'Please enter at least 10 characters in your message.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA check.',
        ]);

        if (
            filled(config('services.recaptcha.secret_key'))
            && !$this->passesRecaptcha($request->input('g-recaptcha-response'), $request->ip())
        ) {
            return back()
                ->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        unset($validated['g-recaptcha-response']);

        $recipients = config('admin.emails') ?: [config('mail.from.address')];

        try {
            Mail::to($recipients)->send(new ContactMessage($validated));
        } catch (\Throwable $e) {
            Log::error('Failed to send contact form submission: ' . $e->getMessage(), [
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);

            return back()
                ->withInput()
                ->with('error', 'We could not send your message right now. Please try again or email us directly.');
        }

        return back()->with('success', 'Thank you for contacting us. We will get back to you soon.');
    }

    protected function passesRecaptcha(?string $token, ?string $ipAddress = null): bool
    {
        $secretKey = config('services.recaptcha.secret_key');

        if (blank($secretKey) || blank($token)) {
            return false;
        }

        $payload = [
            'secret' => $secretKey,
            'response' => $token,
        ];

        if (filled($ipAddress)) {
            $payload['remoteip'] = $ipAddress;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', $payload);

        if (!$response->ok()) {
            return false;
        }

        return (bool) data_get($response->json(), 'success', false);
    }
}
