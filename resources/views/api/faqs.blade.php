
  @if(isset($Info))
        @php($PageID = $Info->Page_ID)
        @php($Content = $Info->faq)   
  @endif

  <div style="font-family: 'Poppins', sans-serif !important;color: #555;line-height: 1.5;font-size: 33px;padding:10px;"> 
           {!! $Content !!}   
  </div>
