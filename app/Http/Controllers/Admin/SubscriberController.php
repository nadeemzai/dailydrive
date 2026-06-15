<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::query()
            ->orderByDesc('subscribed_at')
            ->paginate(20);

        $activeCount       = NewsletterSubscriber::whereNotNull('subscribed_at')->whereNull('unsubscribed_at')->count();
        $unsubscribedCount = NewsletterSubscriber::whereNotNull('unsubscribed_at')->count();

        return view('admin.subscribers.index', compact('subscribers', 'activeCount', 'unsubscribedCount'));
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('status', 'Subscriber deleted.');
    }
}
