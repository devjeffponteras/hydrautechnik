@php
    $customPagesHTML = '<ul class="ms-4">';
    foreach($subPages as $subpage) {
        $customPagesHTML .= '<li><a href="' . url('/') . '/' . $subpage->slug . '" style="color:#2ba6cb;">' . $subpage->label . '</a>';

        if(count($subpage->sub_pages)){
            $customPagesHTML .= view('theme.pages.sitemap-subpages', ['subPages' => $subpage->sub_pages])->render();
        }

        $customPagesHTML .= '</li>';
    }
    $customPagesHTML .= '</ul>';
    echo $customPagesHTML;
@endphp
