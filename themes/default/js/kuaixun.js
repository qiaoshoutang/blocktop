window.onload=function () {
    var list=document.getElementsByClassName("list");
    var text_big=document.getElementsByClassName("text_big")[0];
    var line=document.getElementsByClassName("line")[0];
    var text=document.getElementsByClassName("text")[0];
    var window_onload=document.getElementsByClassName("window_onload")[0];
    var bd=document.getElementsByTagName("body")[0];
    for (var i=0;i<list.length;i++){
        list[i].index=i;
        list[i].onclick=function () {
            var c=this.index;
            line.style.marginLeft=c*(50)+"%";
        }
       /* $(".window_onload").fadeOut(5000);*/

    }
   
};



$(function () {
    var nav = $("#W_Time");
    var win=$(".text");
    var sss =0,ss1=0;

    win.scroll(function () {

        if(sss>win.scrollTop()){
            $(".head,.NavigationBar").show();
            nav.css({
                "position":"relative",
                });
            ss1=1
        }else{
            ss1=0
        }
       /* if(win.width()>=320&&width()<=769){*/
  
               if(win.scrollTop()>=100 && ss1==0){
                   $(".head,.NavigationBar").hide();
                   nav.css({
                       "position":"fixed",
                       "left": 0,
                       "top":0,
                   })
               }
               else {
                   $(".head,.NavigationBar").show();
                   nav.css({
                       "position":"relative",
                      /* "left": "0",
                       "top": "0",*/

                   })
               }
      /*  }*/
        sss=win.scrollTop()-100;
    })

    $("ul.NavigationBar_ul li").click(function(){
        var index = $(this).index();
        index==0?  $("#W_Time").show(): $("#W_Time").hide();
        $(".sbox").hide().eq(index).show();
        $(this).parent().attr('data-type',++index);
        $("input[name='page_p']").val(1);
    })
    
})

