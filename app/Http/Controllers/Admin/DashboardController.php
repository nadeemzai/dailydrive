<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\Article;
use App\Models\NewsletterSubscriber;
use App\Models\NewsSource;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'articleCount'    => Article::count(),
            'aiPublishedCount'=> Article::whereNotNull('ai_generated_at')->count(),
            'pendingCount'    => Article::whereNull('ai_generated_at')->whereNotNull('content_html')->count(),
            'sourceCount'      => NewsSource::count(),
            'providerCount'    => AiProvider::count(),
            'subscriberCount'  => NewsletterSubscriber::whereNotNull('subscribed_at')->whereNull('unsubscribed_at')->count(),
            'latestArticles'  => Article::query()->latest('published_at')->latest('id')->limit(8)->get(),
            'latestSources'   => NewsSource::query()->latest('id')->limit(8)->get(),
            'latestProviders' => AiProvider::query()->orderBy('label')->get(),
        ]);
    }
}
