@php
    $contents = Setting::getTopBar()->contents;
    $styles = Setting::getTopBar()->styles;


    // $socmed = \App\Models\MediaAccounts::all();

    // $socmedHTML = '<div class="mt-4 clearfix">';
    // 	foreach($socmed as $sm){
    // 		$socmedHTML .= '
    // 			<a href="'.$sm->media_account.'" class="social-icon si-small si-rounded si-colored si-'.$sm->name.'" title="'.$sm->name.'" target="_blank">
	//                 <i class="icon-'.$sm->name.'"></i>
	//                 <i class="icon-'.$sm->name.'"></i>
	//             </a>
    // 		';
    // 	}

    // $socmedHTML .= '</div>';


    // $keywords   = ['{Social Media Icons}'];
    // $variables  = [$socmedHTML];

    // $topBarContents = str_replace($keywords,$variables,$contents);
@endphp

<style>
    {!! $styles !!}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Determine elements (support both id-based and class-based markup)
    var input = document.getElementById('exampleInput') || document.querySelector('.search-input');
    var button = document.getElementById('ilros') || document.querySelector('.search-button');

    // Laravel search route (use route helper if available)
    var searchUrl = "{{ route('search.result') }}" || '/search';

    function doSearch() {
        if (!input) return;
        var q = input.value.trim();
        if (!q) {
            // if empty, optionally focus input
            input.focus();
            return;
        }
        // Redirect to search route with query param 'searchtxt' (matches existing codebase)
        var url = searchUrl + '?searchtxt=' + encodeURIComponent(q);
        window.location.href = url;
    }

    if (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            doSearch();
        });
    }

    if (input) {
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault();
                doSearch();
            }
        });
    }
});
</script>

{!! $contents !!}

<!-- Override: Make social icons static (no hover/transform/transition) -->
