s=0;
$(".navigationBtn").on("touchstart",function () {
   
    if(s==0){
        $(this).find($("#Dhl")).slideDown(500);
        s=1;
    }else {
        $(this).find($("#Dhl")).slideUp(500);
        s=0;
    }
});

