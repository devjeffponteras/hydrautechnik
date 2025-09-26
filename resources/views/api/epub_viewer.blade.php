
<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8" />
 
  <meta http-equiv="Content-Security-Policy" content="default-src * 'unsafe-inline' 'unsafe-eval' data: blob:;">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Precious Epub Viewer</title>

  <link rel="stylesheet" type="text/css" href="https://www.beta.ebooklat.phr.com.ph/public/api/css/epub-main-style.css">

  <script src="https://www.beta.ebooklat.phr.com.ph/public/api/js/jszip.min.js"></script>
  <script src="https://www.beta.ebooklat.phr.com.ph/public/api/js/epub.js"></script>
  <!-- <script src="https://www.api.ebooklat.phr.com.ph/public/api/js/localforage.min"></script> -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.3.0/localforage.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  
  <style>
   #toc{
    width: 100%;
    background: transparent;
    border-bottom: 1px solid rgba(0, 0, 0, 0.15);
    padding: 5px;
    display: block;
    font-size: 13px;
    z-index:999;
    width: 98%;
    height: 50px;
   }

   /*#viewer{
    padding-top:30px;
   }
  .spreads{
      padding-top:20px;
      padding-bottom:20px;
   }*/

.sidenav {
    height: 100%;
    width: 310px;
    position: fixed;
    z-index: 9999999;
    top: 0;
    right: -315px;
    background-color: #fff;
    overflow-x: hidden;
    transition: 0.5s;
    box-shadow: 0 6px 12px rgba(0,0,0,0.175);
}

.font-chip{

   display: inline-block;
    vertical-align: middle;
    border-radius: 32px;
    border: 1px solid rgba(0, 0, 0, 0.15);
    text-align: center;
    margin: 4px;
    padding: 4px 8px;

      display: inline-block;
    vertical-align: middle;
    border-radius: 32px;
    border: 1px solid rgba(0, 0, 0, 0.15);
    text-align: center;
    margin: 4px;
    padding: 4px 8px;
    cursor: pointer;
}

.font-setting {
    display: block;
    padding: 8px 12px;
    border-botto
  }

.theme-setting {
    display: inline-block;
    vertical-align: middle;    
    border: 1px solid rgba(0, 0, 0, 0.15);
    text-align: center;
    margin: 4px;
    padding: 4px 8px;
    height: 40px;
    width: 71px;
}

.size-setting {
    display: inline-block;
    vertical-align: middle;
    border-radius: 32px;
    border: 1px solid rgba(0, 0, 0, 0.15);
    text-align: center;
    margin: 4px;
    padding: 4px 8px;
}

.margin-setting {
    display: inline-block;
    vertical-align: middle;
    border-radius: 32px;
    border: 1px solid rgba(0, 0, 0, 0.15);
    text-align: center;
    margin: 4px;
    padding: 4px 8px;
}

  .setting-label {
    display: block;
    font-weight: 700;
    margin-bottom: 8px;
        padding: 5px;
}

.setting-content{
   padding: 4px;
}
  </style>

</head>
<body>
  <!-- <div id="title"></div> -->
  
@if($epub_file_exist)
  
  
  <div style="display:flex;top: 0px;right:0px;background: lightgray;height: 40px;z-index: 99999;position: fixed;">    
        <div style="padding-right: 20px;cursor: pointer;width: 2%; display: contents;padding-left: 4px; color: #fff;">        
        <img src="https://www.beta.ebooklat.phr.com.ph/public/api/img/menu-icon.png" onclick="openSidePanelNav()" style="width: 50px;height: 60px;">                
    </div>
  </div>
   
  <div id="viewer" class="spreads" style="font-size:30px;"></div>
    
   <div style="height:44px;position: fixed;z-index: 999;bottom:40%;text-align:center;width:100%;background: none;">
  <a id="prev" href="#prev" class="arrow" style="font-size: 80px;width: 80px; background: transparent; float: left;margin-left: -20px !important;color: lightgray !important;visibility: visible;height: 100px;line-height: 70px;">‹</a>
  <a id="next" href="#next" class="arrow" style="font-size: 80px;width: 80px; background: transparent; float: right;margin-right: -20px !important;;color: lightgray !important;visibility: visible;height: 100px;line-height: 70px;">›</a>      
  </div>

  <div id="CartPanel" class="sidenav" style="right: -315px;width: 260px;">
      <div id="SidePanelCartDetail" class="header-wrapper sticky-area" style="display: block; background: #21395f !important;padding-top: 10px;">
        <div class="container" style="padding-left: 5px;">
            <div class="topbar d-flex" style="border-bottom: 1.2px solid rgba(255,255,255,.5);">
              <div class="topbar-item-right d-flex ">
                  <div class="item">
                    <div style="display:flex;">
                    <img src="https://www.beta.ebooklat.phr.com.ph/public/api/img/close-button.png" onclick="closeSidePanelNav()" style="color:#fff;font-family: work sans,sans-serif;cursor: pointer;width: 30px;height: 30px;">  
                    <span style="color:#fff;padding-left: 50px;">:: Settings ::</span>
                    </div>
                  </div>
              </div>
            </div>

          </div>
      </div>

       <div class="row" style="margin-right: 0px;margin-left: 0px;"> 
            <div class="table-responsive-md" style="width: 100%;">                  
                <table class="table table-style-01" style="width:100%;">
                  <tbody> 

                       <div class="setting">
                            <div class="setting-label" style="border-bottom: 1px dashed gray;border-top: 1px dashed gray;">Book Chapter</div>
                            <div class="setting-content" data-chips="theme">
                               <select id="toc" style="font-size:15px;padding:5px !important;float:left;"></select>                              
                            </div>
                        </div>

                      <div class="setting">
                          <!-- <div class="setting-label" style="border-bottom: 1px dashed gray;border-top: 1px dashed gray;">Set Bookmark</div>     -->
                          <center style="padding-bottom:10px;">                            
                          <form action="https://www.beta.ebooklat.phr.com.ph/public/api/update-book-marks" method="post">
                            <input type="hidden" value="{{$customer_id}}" name="UserID">
                            <input type="hidden" value="{{$product_id}}" name="ProductID">
                            <input id="BookMarkIndex" type="hidden" name="PageNo">
                             <input type="submit" value=" Bookmark Chapter " style="vertical-align: middle;border-radius: 32px;border: 1px solid rgba(0, 0, 0, 0.15);text-align: center;margin: 4px;padding: 4px 8px;margin-top: 15px;">
                           </form>
                           </center>          
                        </div>

                      <div class="setting">
                            <div class="setting-label" style="border-bottom: 1px dashed gray;border-top: 1px dashed gray;">Themes</div>
                            <div class="setting-content" data-chips="theme">
                                <div class="theme-setting" id="light-theme" style="background: #fff; color: #000;cursor: pointer;" data-default="true" data-value="#fff;#000"></div>
                                <div class="theme-setting" id="dark-theme" style="background: #000; color: #fff;cursor: pointer;" data-value="#000;#fff"></div>                                
                                <div class="theme-setting" id="septia-theme"  style="background: #f5deb3; color: #000;cursor: pointer;" data-value="#f5deb3;#000"></div>                                
                            </div>
                        </div> 

                        <div class="setting">
                            <div class="setting-label" style="border-bottom: 1px dashed gray;border-top: 1px dashed gray;">Font Size</div>
                            <div class="setting-content" data-chips="font-size">        

                                <div id="increase-font" class="theme-setting" id="light-theme" style="background: lightgray; color: #000; width:45%; cursor: pointer;" data-default="true" data-value="#fff;#000">Aa+</div>
                                <div id="decrease-font" class="theme-setting" id="light-theme" style="background: lightgray; color: #000; width:45%; cursor: pointer;" data-default="true" data-value="#fff;#000">Aa-</div>        
                            </div>
                        </div>

                        <div class="setting">
                            <div class="setting-label" style="border-bottom: 1px dashed gray;border-top: 1px dashed gray;">Margins</div>
                            <div class="setting-content" data-chips="font-size">    

                             <div id="increase-margin" class="theme-setting" id="light-theme" style="background: lightgray; color: #000; width:45%; cursor: pointer;" data-default="true" data-value="#fff;#000" >Margin+</div>
                             <div id="decrease-margin"  class="theme-setting" id="light-theme" style="background: lightgray; color: #000; width:45%; cursor: pointer;" data-default="true" data-value="#fff;#000">Margin-</div>                                             
                            </div>
                        </div>
                    
                </tbody>
           </table>                        
          </div>
      </div>           
  </div>

  @else
   <div class="container" style="position: relative; width: 100%px;height: 30%;">
    <center style="position: absolute; top: 50%;width: 300px;left: 13%;">
        <p>Sorry! No ePub book files found nor attached to the books that you are reading.</p>
    </center>
  </div>
  @endif


  <script>
  
    var counter=0;
    var option=0;

    var theme="light";
    var background="white";
    var font_size="14";
    var margin_size="0";
    var font_style="Times Roman";
      
    var params = URLSearchParams && new URLSearchParams(document.location.search.substring(1));
    var url = params && params.get("url") && decodeURIComponent(params.get("url"));
    var currentSectionIndex = (params && params.get("loc")) ? params.get("loc") : undefined;

    var currentSectionIndex ="{{$chapter_page_no}}";
  
    var book = ePub("{{$epub_doc}}", {
      store: "epubjs-test"
    });
     
    var rendition = book.renderTo("viewer", {
      width: "100%",
      height: "100%",
      flow: "scrolled-doc"
    });
   
    var displayed = rendition.display(currentSectionIndex);

    // displayed.then(function(renderer){
    //   // Add all resources to the store      
    //   book.storage.add(book.resources, true).then(() => {
    //     console.log("stored");
    //   })
    // });
   
    // DARK THEME==============================
     
    var dark_theme = document.getElementById("dark-theme");
    dark_theme.addEventListener("click", function(e){  

      theme="dark";
      background="black";
      //rendition.themes.select("dark");  

      rendition.themes.register("dark_color", {
                 body: {                   
                  "background-color": background+ "!important",
                  "font-size": font_size+"px !important",
                  "font-family": "Times New Roman, Times, serif !important",
                },
                "p": {                   
                  "color": "white !important",
                },
          });   

        rendition.themes.select("dark_color");       
              
    });

   // LIGHT THEME =====================================================

   var light_theme = document.getElementById("light-theme");
    light_theme.addEventListener("click", function(e){  
 
      theme="light";
      background="white";
      //rendition.themes.select("dark");  

       rendition.themes.register("light_color", {
                 body: {                   
                     "background-color": background+ "!important",
                  "font-size": font_size+"px !important",
                   "margin": margin_size+"px !important",
                  "font-family": "Times New Roman, Times, serif !important",
                },
                "p": {                   
                  "color": "black !important",
                },
          });

        rendition.themes.select("light_color");        
              
    });


    // SEPIA THEME===========================================================

   var septia_theme = document.getElementById("septia-theme");
    septia_theme.addEventListener("click", function(e){  

      theme="septia";
       background="#f4eacd";
      //rendition.themes.select("dark");     

      rendition.themes.register("septia_color", {
             body: {                   
                "background-color": background+ "!important",
              "font-size": font_size+"px !important",
              "margin": margin_size+"px !important",
              "font-family": "Times New Roman, Times, serif !important",
            },
            "p": {                   
             "color": "#704214 !important",
            },
      });
     
        rendition.themes.select("septia_color");  
       
    });
      
    // End Theme=======================================================================


   // INCRESE FONTS Theme=======================================================================
     
    var increase_font = document.getElementById("increase-font");
     increase_font.addEventListener("click", function(e){  

        font_size++;
                          
         if(theme=='light'){
                 background="white";
                  rendition.themes.register("custom_font", {
                      body: {     
                        "background-color": background+ "!important",
                       "font-size": font_size+"px !important",
                       "margin": margin_size+"px !important",
                      },
                      "p": {                   
                        "color": "black !important",
                      },
                  });

               rendition.themes.select("custom_font");          
         
            }

          if(theme=='dark'){
             background="black";
              rendition.themes.register("custom_font", {
                  body: {     
                    "background-color": background+ "!important",
                    "font-size": font_size+"px !important",
                    "margin": margin_size+"px !important",
                  },
                  "p": {                   
                    "color": "white !important",
                  },
              });

         rendition.themes.select("custom_font");          
         
         }

      if(theme=='septia'){
              background="#f4eacd";
              rendition.themes.register("custom_font", {
                  body: {     
                     "background-color": background+ "!important",
                      "font-size": font_size+"px !important",
                      "margin": margin_size+"px !important",
                  },
                  "p": {                   
                    "color": "#704214 !important",
                  },
              });

             rendition.themes.select("custom_font");                   
           }

       });

      // END INCRESE FONTS

    // DECREASE FONTS Theme=======================================================================
     
    var decrease_font = document.getElementById("decrease-font");
     decrease_font.addEventListener("click", function(e){  

        font_size--;

        if(theme=='light'){
                 background="white";
                  rendition.themes.register("custom_font", {
                      body: {     
                        "background-color": background+ "!important",
                       "font-size": font_size+"px !important",
                       "margin": margin_size+"px !important",
                      },
                      "p": {                   
                        "color": "black !important",
                      },
                  });

               rendition.themes.select("custom_font");          
         
            }

          if(theme=='dark'){
             background="black";
              rendition.themes.register("custom_font", {
                  body: {     
                    "background-color": background+ "!important",
                    "font-size": font_size+"px !important",
                    "margin": margin_size+"px !important",
                  },
                  "p": {                   
                    "color": "white !important",
                  },
              });

         rendition.themes.select("custom_font");          
         
         }

      if(theme=='septia'){
              background="#f4eacd";
              rendition.themes.register("custom_font", {
                  body: {     
                     "background-color": background+ "!important",
                      "font-size": font_size+"px !important",
                      "margin": margin_size+"px !important",
                  },
                  "p": {                   
                    "color": "#704214 !important",
                  },
              });

             rendition.themes.select("custom_font");                   
           }

       });

      // END INCRESE FONTS


    // INCREASE MARGINS Theme=======================================================================
     
    var increase_margin = document.getElementById("increase-margin");
     increase_margin.addEventListener("click", function(e){  

       margin_size++;
                          
         if(theme=='light'){
                 background="white";
                  rendition.themes.register("custom_margin", {
                      body: {     
                        "background-color": background+ "!important",
                       "font-size": font_size+"px !important",
                       "margin": margin_size+"px !important",
                      },
                      "p": {                   
                        "color": "black !important",
                      },
                  });

               rendition.themes.select("custom_margin");          
         
            }

          if(theme=='dark'){
             background="black";
              rendition.themes.register("custom_margin", {
                  body: {     
                    "background-color": background+ "!important",
                    "font-size": font_size+"px !important",
                    "margin": margin_size+"px !important",
                  },
                  "p": {                   
                    "color": "white !important",
                  },
              });

         rendition.themes.select("custom_margin");          
         
         }

      if(theme=='septia'){
              background="#f4eacd";
              rendition.themes.register("custom_margin", {
                  body: {     
                     "background-color": background+ "!important",
                      "font-size": font_size+"px !important",
                      "margin": margin_size+"px !important",
                  },
                  "p": {                   
                    "color": "#704214 !important",
                  },
              });

             rendition.themes.select("custom_margin");                   
           }

       });

      // END INCRESE FONTS


    // DECREASE MARGINS Theme=======================================================================     
    var decrease_margin = document.getElementById("decrease-margin");
     decrease_margin.addEventListener("click", function(e){  

        margin_size--;
                          
         if(theme=='light'){
                 background="white";
                  rendition.themes.register("custom_margin", {
                      body: {     
                        "background-color": background+ "!important",
                       "font-size": font_size+"px !important",
                       "margin": margin_size+"px !important",
                      },
                      "p": {                   
                        "color": "black !important",
                      },
                  });

               rendition.themes.select("custom_margin");          
         
            }

          if(theme=='dark'){
             background="black";
              rendition.themes.register("custom_margin", {
                  body: {     
                    "background-color": background+ "!important",
                    "font-size": font_size+"px !important",
                    "margin": margin_size+"px !important",
                  },
                  "p": {                   
                    "color": "white !important",
                  },
              });

         rendition.themes.select("custom_margin");          
         
         }

      if(theme=='septia'){
              background="#f4eacd";
              rendition.themes.register("custom_margin", {
                  body: {     
                     "background-color": background+ "!important",
                      "font-size": font_size+"px !important",
                      "margin": margin_size+"px !important",
                  },
                  "p": {                   
                    "color": "#704214 !important",
                  },
              });

             rendition.themes.select("custom_margin");                   
           }

       });

      // END INCRESE FONTS


     // End Set Custome Theme===============================================================

    book.ready.then(() => {

      var next = document.getElementById("next");
      next.addEventListener("click", function(e){        
        book.package.metadata.direction === "rtl" ? rendition.prev() : rendition.next();

       var $select = document.getElementById("toc");
       index=$select.selectedIndex+1; 

        var $ChapterNo = document.getElementById("BookMarkIndex");
        $ChapterNo.value=index;  
                    
        e.preventDefault();      
      }, false);

      var prev = document.getElementById("prev");
      prev.addEventListener("click", function(e){
        book.package.metadata.direction === "rtl" ? rendition.next() : rendition.prev();    
       
       var $select = document.getElementById("toc");
       index=$select.selectedIndex-1; 

       var $ChapterNo = document.getElementById("BookMarkIndex");
        $ChapterNo.value=index;     
        
      }, false);

      var keyListener = function(e){

        // Left Key
        if ((e.keyCode || e.which) == 37) {
          book.package.metadata.direction === "rtl" ? rendition.next() : rendition.prev();
        }

        // Right Key
        if ((e.keyCode || e.which) == 39) {
          book.package.metadata.direction === "rtl" ? rendition.prev() : rendition.next();
        }

      };

      rendition.on("keyup", keyListener);
      document.addEventListener("keyup", keyListener, false);

    })

    var title = document.getElementById("title");

    rendition.on("rendered", function(section){
      var current = book.navigation && book.navigation.get(section.href);

      if (current) {
        var $select = document.getElementById("toc");
        var $selected = $select.querySelector("option[selected]");
        if ($selected) {
          $selected.removeAttribute("selected");
        }

        var $options = $select.querySelectorAll("option");
        for (var i = 0; i < $options.length; ++i) {
          let selected = $options[i].getAttribute("ref") === current.href;
          if (selected) {
            $options[i].setAttribute("selected", "");
          }
        }
      }

    });

    rendition.on("relocated", function(location){
      console.log(location);

      var next = book.package.metadata.direction === "rtl" ?  document.getElementById("prev") : document.getElementById("next");
      var prev = book.package.metadata.direction === "rtl" ?  document.getElementById("next") : document.getElementById("prev");

      if (location.atEnd) {
        next.style.visibility = "hidden";
      } else {
        next.style.visibility = "visible";
      }

      if (location.atStart) {
        prev.style.visibility = "hidden";
      } else {
        prev.style.visibility = "visible";
      }
      
    });

    rendition.on("layout", function(layout) {

      let viewer = document.getElementById("viewer");

      if (layout.spread) {
        viewer.classList.remove('single');
      } else {
        viewer.classList.add('single');
      }
    });

    window.addEventListener("unload", function () {
      console.log("unloading");
      this.book.destroy();
    });

    book.loaded.navigation.then(function(toc){
      var $select = document.getElementById("toc"),
          docfrag = document.createDocumentFragment();

      toc.forEach(function(chapter) {
        var option = document.createElement("option");
        option.textContent = chapter.label;
        option.setAttribute("ref", chapter.href);

        docfrag.appendChild(option);
      });

      $select.appendChild(docfrag);

      $select.onchange = function(){
           index = $select.selectedIndex,
              url = $select.options[index].getAttribute("ref");
          rendition.display(url);

           var $ChapterNo = document.getElementById("BookMarkIndex");
           $ChapterNo.value=index;   
          
          return false;
      };
    });

  </script>

  <script>
    function openSidePanelNav() {
    document.getElementById("CartPanel").style.right = "0px";
   }
   function closeSidePanelNav() {
    document.getElementById("CartPanel").style.right = "-315px";
   }
  </script>
  

  </body>
</html>
