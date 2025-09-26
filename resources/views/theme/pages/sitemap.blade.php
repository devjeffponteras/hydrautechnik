@extends('theme.main')

@section('pagecss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endsection

@php
    // FOR THE MAIN PAGE CONTENT
    $contents = $page->contents;
    

    //PAGES
    if($customPages->count()) {

        $customPagesHTML = '';

        foreach ($customPages as $index => $cpage) {
            
            $customPagesHTML .= '
            <li><a href="' . url('/') . '/' . $cpage->slug . '" style="color:#2ba6cb;">' . $cpage->label . '</a>';

            if(count($cpage->sub_pages)){
                $customPagesHTML .= view('theme.pages.sitemap-subpages', ['subPages' => $cpage->sub_pages])->render();
            }

            $customPagesHTML .= '
            </li>';
        }

    } else {
        $customPagesHTML = '';
    } 



    //CATEGORY
    if($articleCategories->count()) {

        $articleCategoriesHTML = '';

        foreach ($articleCategories as $index => $cat) {

            $articles = \App\Models\Article::where('category_id', $cat->id)->where('status', 'PUBLISHED')->get();

            if($articles->count()) {

                $articleCategoriesHTML .= '
                <li><a href="' . url('/news') . '?type=category&criteria='. $cat->id .'"><strong>'. $cat->name .'</strong></a>
                    <ul class="ms-4">';

                    foreach($articles as $article){
                        $articleCategoriesHTML .= '<li><a href="' . url('/news') . '/'. $article->slug. '" style="color:#2ba6cb;">'. $article->name. '</a></li>';
                    }

                $articleCategoriesHTML .= '
                    </ul>
                </li>';
                
            }
            
        }

    } else {
        $articleCategoriesHTML = '';
    } 

    

    //ARTICLES
    $articles = \App\Models\Article::where('status', 'PUBLISHED')->get();

    if($articles->count()) {

        $articlesHTML = '';

        foreach($articles as $article){
            $articlesHTML .= '<li><a href="' . url('/news') . '/'. $article->slug. '" style="color:#2ba6cb;">'. $article->name. '</a></li>';
        }

    } else {
        $articlesHTML = '';
    } 
    
    $keywords   = ['{Pages}', '{Category}', '{Articles}'];
    $variables  = [$customPagesHTML, $articleCategoriesHTML, $articlesHTML];
    $contents = str_replace($keywords,$variables,$contents);

@endphp



@section('content')
    {!! $contents !!}
@endsection


@section('pagejs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
@endsection

