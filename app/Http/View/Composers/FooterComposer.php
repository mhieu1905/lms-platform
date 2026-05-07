<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\FooterSection;

class FooterComposer
{
    public function compose(View $view)
    {
        $footers = FooterSection::orderByDesc('id')->get();

        $footersWithLogo = $footers->filter(function ($footer) {
            $footer->content = json_decode($footer->content);
            return isset($footer->content->logo);
        });
        $footersWithTitle = $footers->filter(function ($footer) {
            return isset($footer->content->title);
        });
        $footersWithCopyright = $footers->filter(function ($footer) {
            return isset($footer->content->copyright);
        });
        $footersWithSocial = $footers->filter(function ($footer) {
            return isset($footer->content->socials);
        });

        $view->with(compact(
            'footers',
            'footersWithLogo',
            'footersWithTitle',
            'footersWithCopyright',
            'footersWithSocial'
        ));
    }
}
