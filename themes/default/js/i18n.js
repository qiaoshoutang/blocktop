(function(win) {
  var opts = {
    element: document,
    lang: (function() {
      var tyLang = localStorage.tyLang;

        if (tyLang && tyLang.length > 0 && tyLang != "undefined") {
        return tyLang;
      }
      return "zh";

    })(), //en | jap,




    i18n: {
      //key编码规则：取前2个中文字符拼音+最后2个中文字符拼音
      //不满4个中文字符用e代表
        en: {
            header01:'Home',
            header02:"News",
            header021:"Industry News",
            header022:"News Flash",
            header03:"Featured",
            header031:"Market",
            header032:"Asset Management",
            header033:"Applications",
            header034:"Wallets",
            header035:"Mining Pool",
            header036:"Media",
            header037:"Technology Service",
            header038:"Community",
            header04:"Activities",
            header041:"Signing up",
            header042:"Completed",
            header05:"About Us",
            header051:"Introduction",
            header052:"Team",
            header053:"Contact Us",
            AntZhiContentA:"Founded in November 2017, Blockchain Headlines is a We-Media platform under Ant Node Alliance. It is one of the most influential We-Media brands in blockchain industry.",
            AntZhiContent:"Blockchain Headlines includes news flash, in-depth reports, market analysis, blockchain class, etc. It keeps reporting news on a 24/7 basis, adheres to the notion of “being objective, accurate, and in-depth”, and provides service to the global blockchain ecosystem. Blockchain Headlines includes 4 major sections: news, community, blockchain class, and market analysis . Meanwhile, Blockchain Headlines occasionally holds “Blockchain Headlines Off-line Party”, inviting elites from blockchain industry to create a professional learning platform for our followers.",
            TimeFzMaCao:"Sept.21st-22nd,2019",
            FzMaCao01:"The 6th session of FINWISE summit",
            FzMaCao02:"Global summit·Macao",
            FzMaCao03:"Organized by Ant Node Alliance",
            TimeFzHK:"Aug.10th-11th, 2018",
            FzHK01:"The 4th session of FINWISE summit·HK",
            FzHK02:"Co-hosted by Ant Node Alliance",
            TimeFzTokyo:"May.21st-22nd,2018",
            FzTokyo01:"The 3rd session of FINWISE summit·Tokyo",
            FzTokyo02:"Zeng Hao delivered a significant speech",
            FzTokyo03:"Co-hosted by Ant Node Alliance",
            Discussions:"Mar.20th,2018",
            Discussions01:"Discussions at NPC&CPPCC, 2018",
            Discussions02:"Committee member Wu Jiezhuang highly praised Ant Note Alliance",
            MacaoSecont:"Jan.12th-13th,2018",
            MacaoSecont01:"The 2nd session of FINWISE summit·Macao",
            MacaoSecont02:"Co-hosted by Ant Node Alliance",
            MacaoSecont03:"Officially announced the establishment of Ant Note Alliance",
            FiveFzHzTime:"May.28th-29th,2019",
            FiveFzHz01:"The 5th session of FINWISE",
            FiveFzHz02:"summit·HK",
            FiveFzHz03:"Organized by Ant Node Alliance",
            HainanTime:"Jun.29th-30th,2018",
            Hainan01:"The 2nd session of Hainan International High-tech industry and Innovation Expo,",
            Hainan02:"also the 1st Global Blockchain Technology Achievements Forum",
            Hainan03:"Organized by Ant Node Alliance",
            JiezhuangTime:"April.9th,2018",
            Jiezhuang01:"Wu Jiezhuang, member of the national",
            Jiezhuang02:"committee of CPPCC,visited Ant Node Alliance and gave in-depth guidance to",
            Jiezhuang03:"the company",
            LxFhTime:"Jan 26th,2018",
            LxFh01:"Blockchain Leader Summit",
            LxFh02:"also the annual conference of Ant Node Alliance",
            establishedTime:"May,2017",
            established:"Fujian Blockchain Club was established",
            HonorsAndAwards01:"Blockchain Industry Alliance of China High-Tech Industrialization Research Association",
            HonorsAndAwards02:"Vice-president unit",
            HonorsAndAwards03:"Executive Committee member of Conference and Exhibition Development Committee",
            HonorsAndAwards04:"And Domestic Affairs Development Committee",
            HonorsAndAwards05:"Strategic Cooperation Unit of University of Chinese Academy of Sciences",
            HonorsAndAwards06:"Strategic Cooperation Unit of Xiamen University Blockchain Research Center",
            HonorsAndAwards07:"Founding Chairman Unit of Xiamen Blockchain Association",
            HonorsAndAwards08:"Executive Chairman Unit of Guangdong-HK-Macao Greater Bay Area Media Alliance",
            HonorsAndAwards09:"Golden Globe Award and Excellent Community Alliance Award of he 5th session of FINWISE summit·HK(May.28th- 29th, 2019)",
            ZengHao:"ZengHao",
            ZhangJingWei:"ZhangJingwei",
            YangZhiWen:"YangZhiwen",
            appellationZH01:"Vice-president of China High-tech Blockchain Industry Alliance",
            appellationZH02:"Founder of Ant Node Alliance",
            appellationZH03:"Founder of Ant Alliance International Capital",
            appellationZJW01:"Co-founder of Ant Node Alliance",
            appellationZJW02:"Co-founder of Ant Alliance International Capital",
            appellationZJW03:"",
            appellationYZW01:"Co-founder of Ant Node Alliance",
            appellationYZW02:"Co-founder of Ant Alliance International Capital",
            appellationYZW03:"",
            LearnMore:'Learn more',
            ourBrands:"As a high quality blockchain we-media platform with 50,000+ followers under the Ant Node Alliance, Block-Hunt is committed to building the most influential financial and economic media.",
            ourBrandsBz:"Based on the application and token mechanism of LPChain, Link People solves the problem of the trust and incentives among internet users. ",
            ourBrandsFz:"With a team of experienced financial market experts and blockchain elites, FINWISE is one of the most influential fintech summits.",
            FollowUs:"Follow us",
            Didian:"Huli District,Xiamen City",
            ManagementConsult:"Project",
            ManagementTextA:"Consulting",
            ManagementTextB:" ",
            ManagementTextC:" ",
            ManagementConsult01:"Industry",
            ManagementTextA01:"Conferences",
            ManagementTextB01:" ",
            ManagementTextC01:" ",
            ManagementConsult02:"Blockchain",
            ManagementTextA02:"Education",
            ManagementTextB02:" ",
            ManagementTextC02:" ",
            ManagementConsult03:"Community",
            ManagementTextA03:"Operations",
            ManagementTextB03:" ",
            ManagementTextC03:" ",
            ManagementConsult04:"Public",
            ManagementTextA04:"Relations",
            ManagementTextB04:" ",
            ManagementTextC04:" ",
            ManagementConsult05:"TOP",
            ManagementTextA05:"Exchanges",
            ManagementTextB05:" ",
            ManagementTextC05:" ",
            cricleTaday:"Today",
            All:"All",
            SigningUp:"Signing up",
            Completed:"Completed ",
            LastWeek:"Last week",
            LastMonth:"Last month",
            Last6months:"Last 6 months",
            Earlier:'Earlier',
            more:"more",
            NoMore:"No more"
        },
        zh: {
            header01:'首页',
            header02:'新闻资讯',
            header021:"新闻动态",
            header022:"快讯",
            header03:"头条导航",
            header031:"行情",
            header032:"资产管理",
            header033:"应用",
            header034:"钱包",
            header035:"矿池",
            header036:"媒体",
            header037:"技术服务",
            header038:"社区",
            header04:"活动",
            header041:"报名中",
            header042:"已结束",
            header05:"关于我们",
            header051:"简介",
            header052:"团队",
            header053:"联系我们",
            AntZhiContentA:"区块链头条成立于2017年11 月，是新锐区块链媒体品牌。",
            AntZhiContent:"区块链头条集快讯、时讯、深研、行情、课堂等为一体。7*24 小时行业资讯追踪报道，秉承“客观、真实、深度”的理念，  服务全球区块链领域生态。目前，区块链头条覆盖粉丝数20W+。传统媒体平台： 微博、搜狐、简书、知乎、今日头条、凤凰网、财经网等。",

            TimeFzMaCao:"2019年9月21日-22日",
            FzMaCao01:"2019FINWISE纷智第六届",
            FzMaCao02:"全球峰会·澳门站",
            FzMaCao03:"蚂蚁节点联盟承办",
            TimeFzHK:"2018年8月10日-11日",
            FzHK01:"FINWISE纷智第四届·香港峰会",
            FzHK02:"蚂蚁节点联盟联合主办",
            TimeFzTokyo:"2018年5月21日-22日",
            FzTokyo01:"Finwise纷智第三届·东京峰会",
            FzTokyo02:"豪哥发表重要演讲",
            FzTokyo03:"蚂蚁节点联盟联合主办",
            Discussions:"2018年3月20日",
            Discussions01:"2018两会大家谈",
            Discussions02:"吴杰庄委员盛赞蚂蚁节点联盟",
            MacaoSecont:"2018年1月12日-13日",
            MacaoSecont01:"FINWISE纷智第二届·澳门峰会",
            MacaoSecont02:"蚂蚁节点联盟联合主办",
            MacaoSecont03:"正式全球发布蚂蚁节点联盟成立",
            FiveFzHzTime:"2019年5月28日-29日",
            FiveFzHz01:"2019FINWISE纷智第五届",
            FiveFzHz02:"全球峰会·香港站",
            FiveFzHz03:"蚂蚁节点联盟承办",
            HainanTime:"2018年6月29日-30日",
            Hainan01:"2018第二届海创会暨",
            Hainan02:"首届全球区块链技术成果论坛",
            Hainan03:"蚂蚁节点联盟承办",
            JiezhuangTime:"2018年4月9日",
            Jiezhuang01:"全国政协委员吴杰庄",
            Jiezhuang02:"莅临蚂蚁节点联盟",
            Jiezhuang03:"深度交流及指导工作",
            LxFhTime:"2018年1月26日",
            LxFh01:"区块链领袖峰会",
            LxFh02:"暨蚂蚁节点联盟年会",
            establishedTime:"2017年5月",
            established:"福建区块链俱乐部成立",
            HonorsAndAwards01:"中国高科技产业化研究会区块链产业联盟",
            HonorsAndAwards02:"副理事长单位",
            HonorsAndAwards03:"会务会展发展委员会",
            HonorsAndAwards04:"国内事务发展委员会执委",
            HonorsAndAwards05:"中国科学院大学数字经济与区块链研究中心战略合作单位",
            HonorsAndAwards06:"厦门大学区块链研究中心战略合作单位",
            HonorsAndAwards07:"厦门区块链协会创始会长单位",
            HonorsAndAwards08:"粤港澳大湾区媒体联盟执行会长单位",
            HonorsAndAwards09:"2019年5月28日第五届纷智峰会·香港站，纷智金球奖-获优秀社区联盟奖",
            ZengHao:"曾豪",
            ZhangJingWei:"张竟唯",
            YangZhiWen:"杨志文",
            appellationZH01:"中高会区块链产业联盟副理事长",
            appellationZH02:"蚂蚁节点联盟创始人",
            appellationZH03:"蚂蚁联盟国际资本创始人",
            appellationZJW01:"蚂蚁节点联盟联合创始人",
            appellationZJW02:"蚂蚁联盟国际资本联合创始人",
            appellationZJW03:"",
            appellationYZW01:"蚂蚁节点联盟联合创始人",
            appellationYZW02:"蚂蚁联盟国际资本联合创始人",
            appellationYZW03:"",
            LearnMore:'了解更多',
            ourBrands:"蚂蚁节点联盟旗下优质区块链自媒体平台，致力打造最具行业影响力的财经媒体品牌，粉丝数5w+",
            ourBrandsBz:"币众是基于LPChain理念开发的应用，利用LPChain的通证机制解决网络用户之间的信任和激励问题，实现用户各类",
            ourBrandsFz:"FINWISE纷智峰会是全球最具影响力的金融科技峰会之一，团队由经验丰富的金融市场专家和区块链行业精英组成",
            FollowUs:"关注我们",
            Didian:"厦门市湖里区",
            ManagementConsult:"项目咨询管理",
            ManagementTextA:"制定中短期及长期运营解决",
            ManagementTextB:"方案，服务期间对项目进行",
            ManagementTextC:"市场监控与管理",
            ManagementConsult01:"行业会议",
            ManagementTextA01:"专业承办行业千人峰会",
            ManagementTextB01:"提供策划-推广-会务",
            ManagementTextC01:"等一条龙服务",
            ManagementConsult02:"教育培训",
            ManagementTextA02:"依托国家级教育培训资源",
            ManagementTextB02:"提供专业教育咨询服务",
            ManagementTextC02:" ",
            ManagementConsult03:"社群运营",
            ManagementTextA03:"线上专业的微信社群",
            ManagementTextB03:"管理团队",
            ManagementTextC03:" ",
            ManagementConsult04:"投行上所",
            ManagementTextA04:"全球Top30",
            ManagementTextB04:"主流交易所资源对接",
            ManagementTextC04:" ",
            ManagementConsult05:"媒体公关",
            ManagementTextA05:"覆盖40+行业主流媒体",
            ManagementTextB05:"提供全方位媒体宣传服务",
            ManagementTextC05:" ",
            cricleTaday:"今天",
            All:"全部",
            SigningUp:"报名中",
            Completed:"已结束",
            LastWeek:"近一周",
            LastMonth:"近一月",
           Last6months:"近半年",
            Earlier:'更早',
            more:'查看更多',
            NoMore:"已经没有更多了"


},
    },
    dom: {
      domI18n: "dom-i18n"
    }
  };
  var ins = {
    init: function() {
      this.initDom();
      this.render();
      this.addListen();
    },
    initDom: function() {
      ins.utils.each(opts.dom, function(item, key) {
        var doms = opts.element.querySelectorAll("[" + item + "]");
        opts.dom[key] = doms;
      });
    },
    render: function() {
      ins.utils.each(opts.dom.domI18n, function(dom, index) {
        //获取当前dom的文字对应key
        var key = dom.getAttribute("dom-i18n");
        //获取当前语言
        var lang = opts.i18n[opts.lang],
          txt = lang[key];
        if (txt && txt.length > 0) {
          //渲染文字
          dom.innerText = txt;
        }
      });
    },
    setLang: function(v) {
      opts.lang = v;

      // 通过语言区分页面 begin
      //   $('body').addClass('zhlang');
        if(v == 'zh'){
            $('body').addClass('zhlang');
            $('body').removeClass('otherlang');
        }else {
            $('body').addClass('otherlang');
            $('body').removeClass('zhlang');
        }
        // 通过语言区分页面 end

    },


    addListen: function() {
      Object.defineProperty(opts, "lang", {
        get: function() {
          return lang;
        },
        set: function(v) {
          lang = v;
          ins.render();
          localStorage.tyLang = v;
        }
      });

    },
    utils: {
      each: function(obj, callback) {
        //针对ie8
        if (obj == "[object StaticNodeList]") {
          for (var i = 0; i < obj.length; i++) {
            callback(obj[i], i);
          }
          return;
        }
        //针对正常浏览器
        if (this.isArray(obj)) {
          for (var i = 0; i < obj.length; i++) {
            callback(obj[i], i);
          }
        }
        if (this.isNodeList(obj)) {
          for (var i = 0; i < obj.length; i++) {
            callback(obj[i], i);
          }
        }
        if (this.isObject(obj)) {
          for (var key in obj) {
            callback(obj[key], key);
          }
        }
      },
      isString: function(obj) {
        return Object.prototype.toString.call(obj) == "[object String]";
      },
      isArray: function(obj) {
        return Object.prototype.toString.call(obj) == "[object Array]";
      },
      isNodeList: function(obj) {
        return Object.prototype.toString.call(obj) == "[object NodeList]";
      },
      isObject: function(obj) {
        return Object.prototype.toString.call(obj) == "[object Object]";
      },
      isNull: function(obj) {
        return Object.prototype.toString.call(obj) == "[object Null]";
      }
    }
  };

  ins.init();
  win.i18n = ins;

})(window);


// 通过语言区分页面 begin
// $('body').addClass('zhlang');
var tyLang = localStorage.tyLang;
if(tyLang == 'zh'){
    $('body').addClass('zhlang');
    $('body').removeClass('otherlang');
    FoundingTeam();
    Zh()
}else {
    $('body').addClass('otherlang');
    $('body').removeClass('zhlang');
    En()
    FoundingTeamEn()
}
(()=>{
    // 兼容IE
    if(document.all) {
        document.getElementById("zh-lang").click();
    }
    // 兼容其它浏览器
    else {
        var e = document.createEvent("MouseEvents");
        e.initEvent("click", true, true);
        document.getElementById("zh-lang").dispatchEvent(e);
    }
})();


$(".zh-lang").click(function () {
    Zh()
    FoundingTeam()
});
$(".en-lang").click(function () {
    En()
    FoundingTeamEn()
});
function FoundingTeam(){
    $(".List_Team ul li").click(function () {
        if ($(this).index() == 0) {
            $(".pictureTx img").attr("src", "/themes/default/images/zenghao.png");
            $(".NameFound").html("曾豪")
            $(".appellation p").eq(0).html("中高会区块链产业联盟副理事长");
            $(".appellation p").eq(1).html("蚂蚁节点联盟创始人");
            $(".appellation p").eq(2).html("蚂蚁联盟国际资本创始人");
        }
        if ($(this).index() == 1) {
            $(".pictureTx img").attr("src", "/themes/default/images/zhangjingwei.png")
            $(".NameFound").html("张竟唯");
            $(".appellation p").eq(0).html("蚂蚁节点联盟联合创始人");
            $(".appellation p").eq(1).html("蚂蚁联盟国际资本联合创始人");
            $(".appellation p").eq(2).html("");
        }
        if ($(this).index() == 2) {
            $(".pictureTx img").attr("src", "/themes/default/images/yangzhiwen.png")
            $(".NameFound").html("杨志文")
            $(".appellation p").eq(0).html("蚂蚁节点联盟联合创始人");
            $(".appellation p").eq(1).html("蚂蚁联盟国际资本联合创始人");
            $(".appellation p").eq(2).html("");
        }
    })
}
function FoundingTeamEn(){
    $(".List_Team ul li").click(function () {
        if ($(this).index() == 0) {
            $(".pictureTx img").attr("src", "/themes/default/images/zenghao.png");
            $(".NameFound").html("ZengHao")
            $(".appellation p").eq(0).html("Vice-president of China High-tech Blockchain Industry Alliance");
            $(".appellation p").eq(1).html("Founder of Ant Node Alliance");
            $(".appellation p").eq(2).html("Founder of Ant Alliance International Capital");
        }
        if ($(this).index() == 1) {
            $(".pictureTx img").attr("src", "/themes/default/images/zhangjingwei.png")
            $(".NameFound").html("ZhangJingwei")
            $(".appellation p").eq(0).html("Co-founder of Ant Node Alliance");
            $(".appellation p").eq(1).html("Co-founder of Ant Alliance International Capital");
            $(".appellation p").eq(2).html("");
        }
        if ($(this).index() == 2) {
            $(".pictureTx img").attr("src", "/themes/default/images/yangzhiwen.png")
            $(".NameFound").html("YangZhiwen")
            $(".appellation p").eq(0).html("Co-founder of Ant Node Alliance");
            $(".appellation p").eq(1).html("Co-founder of Ant Alliance International Capital");
            $(".appellation p").eq(2).html("");
        }
    })
}

function Zh(){
    // $(".header-list").css("width","600px");
    // $(".sub-page-nav").css("width","70%");
    // $(".header-list li").css("width","25%");
    $(".AntVideo").css("height","1100px");
    $(".AntZhi").removeClass("AntZhiContent");
    $(".HisDetail").css("font-size","27.15px");
    // $(".search-i").attr("placeholder","搜索");
    $(".ourBrands-ul li:nth-child(1)").css("background-image","url(/themes/default/images/outBrandsList.png)")
    $(".zhshow").show();
    $(".enshow").hide()
    $(".font-Mana").css("font-size","15px")
    $(".management-Title").removeClass("topServe")
    $('.briefbanner').css("background-image","url(/themes/default/images/briefbanner.png)");
    $(".taday").css("font-size","30px");
    // $(".search-i").css({
    //     "font-size":"16px"
    // })
    $(".Recent-time ul li").css({
        "width": "85px",
        "font-size":"20px"
    })
    $(".RecentnavList_ul li").css({
        "width": "8%"
    })

}

function En() {
    // $(".header-list").css("width","800px");
    // $(".sub-page-nav").css("width","85%");
    // $(".header-list li").css("width","18%");
    // $(".header-list li").eq(2).css("width","28%");
    $(".AntVideo").css("height","1250px");
    $(".AntZhi").addClass("AntZhiContent");
    $(".HisDetail").css("font-size","20px");
    $(".search-i").attr("placeholder","search");
    $(".ourBrands-ul li:nth-child(1)").css("background-image","url(/themes/default/images/BLOCK-HUNT.png)");
    $(".ourBrands-ul li:nth-child(2)").css("background-image","url(/themes/default/images/LINKPEOPLE.png)");
    $(".ourBrands-ul li:nth-child(3)").css("background-image","url(/themes/default/images/FINWISESUMMIT.png)");
    $(".zhshow").hide();
    $(".enshow").show();
    $(".font-Mana").css("font-size","23px");
    $(".management-Title").addClass("topServe")
    $('.briefbanner').css("background-image","url(/themes/default/images/briefbanner.png)")
    $(".taday").css("font-size","23px");
    // $(".search-i").css({
    //     "font-size":"12px"
    // })
    $(".Recent-time ul li").css({
        "width": "120px",
        "font-size":"16px"
    })
    $(".Recent-time ul li:nth-child(1)").css({
        "width": "85px",
        "font-size":"16px"
    })
    $(".RecentnavList_ul li").css({
        "width": "12%"
    })
    $(".RecentnavList_ul li:nth-child(1)").css({
        "width": "8%"
    })
}








// 通过语言区分页面 end