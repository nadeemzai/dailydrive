<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:190'],
            'name'  => ['nullable', 'string', 'max:100'],
        ]);

        $email = strtolower(trim($request->email));

        $subscriber = NewsletterSubscriber::firstOrNew(['email' => $email]);

        if ($subscriber->isActive()) {
            return back()->with('newsletter_status', 'already_subscribed');
        }

        $subscriber->name            = $request->name ?: $subscriber->name;
        $subscriber->subscribed_at   = now();
        $subscriber->unsubscribed_at = null;
        $subscriber->token           = $subscriber->token ?: Str::random(64);
        $subscriber->save();

        return back()->with('newsletter_status', 'subscribed');
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->firstOrFail();
        $subscriber->update(['unsubscribed_at' => now()]);

        return view('newsletter.unsubscribed');
    }
}
