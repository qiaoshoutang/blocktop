
s=0;
y=0;
$(document).ready(function(){
    $(".navigationBtn").on("click",function () {
        if(s==0){
            $(this).next($("#Dhl")).slideDown(500);
            s=1;
        }else {
            $(this).next($("#Dhl")).slideUp(500);
            s=0;
        }
    });


    $("#Loginsuccess").click(function () {
        if(s==0){
            $(this).next(".Login-successful").show()
            s=1;
        }else {
            $(this).next(".Login-successful").hide()
            s=0;
        }

    })
});


