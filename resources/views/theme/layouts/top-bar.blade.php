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

{!! $contents !!}