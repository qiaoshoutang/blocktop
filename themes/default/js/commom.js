$(".header-list li").hover(function () {
    $(this).find($(".sub-page-navH")).stop(true, false).slideDown(300);
},function () {
    $(this).find($(".sub-page-navH")).stop(true, false).slideUp(300);
});
$(".sub-page-navH").hover(function () {
    $(this).prev().addClass("sub-page-firstAddclass");
},function () {
    $(this).prev().removeClass("sub-page-firstAddclass");
})

// var meta="<meta name=\"baidu-site-verification\" content=\"9h2o7ScMIE\" />";
// $("head").prepend(meta);



