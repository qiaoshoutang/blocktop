s=0;
$(".Dropdown").on("touchstart",function () {
    if(s==0){
        $(this).find($("#Dhl")).slideDown(500);
        s=1
    }else {
        $(this).find($("#Dhl")).slideUp(500);
        s=0
    }
});

// var meta="<meta name=\"baidu-site-verification\" content=\"9h2o7ScMIE\" />";
// $("head").prepend(meta);
// var metaN=" <meta name=\"keywords\" content=\"蚂蚁节点联盟，社群组织，区块链，区块链社区，区块链社区联盟，区块链服务平台\"/>";
// $("head").prepend(metaN);
// var metaN=" <meta name=\"description\" content=\"蚂蚁节点联盟-蚂蚁节点联盟是国内知名的区块链线下社群组织，联盟以引领行业新生态、 促进区块链行业稳健发展为宗旨，致力于聚合全球区块链行业专家及区块链爱好者， 整合区块链产业上下游资源，为区块链行业参与者提供第三方综合服务。\"/>";
// $("head").prepend(metaN);