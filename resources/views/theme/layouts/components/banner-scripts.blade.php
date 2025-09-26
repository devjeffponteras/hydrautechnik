@if(isset($page) && $page->album && count($page->album->banners) > 0 && $page->album->is_main_banner())
    <script type="text/javascript">
        var bannerFxIn = "{{SettingHelper::bannerTransition($page->album->transition_in)}}";
        var bannerFxOut = "{{SettingHelper::bannerTransition($page->album->transition_out)}}";
        var autoPlayTimeout = "{{$page->album->transition*1000}}";
    </script>
@elseif(isset($page) && $page->album && count($page->album->banners) > 1 && !$page->album->is_main_banner())
   <script type="text/javascript">
        var bannerFxIn = "{{SettingHelper::bannerTransition($page->album->transition_in)}}";
        var bannerFxOut = "{{SettingHelper::bannerTransition($page->album->transition_out)}}";
        var autoPlayTimeout = "{{$page->album->transition*1000}}";
    </script>
@else
    <script type="text/javascript">
        var bannerFxIn = "bounceIn";
        var bannerFxOut = "bounceOut";
        var autoPlayTimeout = "4000";
    </script>
@endif


<script type="text/javascript">
    var bannerCaptionFxIn = "fadeInUp";
    var bannerID = "banner";
</script>