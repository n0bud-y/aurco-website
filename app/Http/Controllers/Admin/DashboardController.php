<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\GalleryImage;
use App\Models\Message;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\TrackRecord;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Projects', 'value' => Project::count(), 'route' => route('admin.projects.index')],
                ['label' => 'Track record rows', 'value' => TrackRecord::count(), 'route' => route('admin.track-records.index')],
                ['label' => 'Gallery photos', 'value' => GalleryImage::count(), 'route' => route('admin.gallery.index')],
                ['label' => 'Services', 'value' => Service::count(), 'route' => route('admin.services.index')],
                ['label' => 'Testimonials', 'value' => Testimonial::count(), 'route' => route('admin.testimonials.index')],
                ['label' => 'FAQs', 'value' => Faq::count(), 'route' => route('admin.faqs.index')],
                ['label' => 'Blog posts', 'value' => Post::count(), 'route' => route('admin.posts.index')],
            ],
            'unread' => Message::whereNull('read_at')->count(),
            'messages' => Message::latest()->take(5)->get(),
        ]);
    }
}
