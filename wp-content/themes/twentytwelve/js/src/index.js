/**
 * Created with JetBrains WebStorm.
 * User: ty
 * Date: 13-11-28
 * Time: 下午4:42
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function(){

    //窗口滚动事件
    $(window).scroll(function(){
        ZY.windowScroll();
    });

    //菜单点击事件
    $("#menu li>a").click(function(){

        //如果页面上的图片资源没有加载完成，不能响应
        if(document.readyState=="complete"){
            var target=$(this).attr("href");
            ZY.scrollToTarget($(target));
        }

        return false;
    });
    $("#logo").click(function(){
        ZY.scrollToTarget($("#topHeader"));

        return false;
    });

    //点击向下按钮
    $("#down").click(function(){
        ZY.scrollToTarget($("#index"));
    });

    //产品分类点击
    $("#productType a").click(function(){
        ZY.productTypeClickHandler($(this));

        return false;
    });

    //前一页，后一页按钮
    $("#product").hover(function(){
        ZY.showPageNav();
    },function(){
        ZY.hidePageNav();
    });
    $("#prevPage").click(function(){
         ZY.prevPageClickHandler();
    });
    $("#nextPage").click(function(){
         ZY.nextPageClickHandler();
    });

    //显示文章
    $(".inviteTypes a,.productList a,.topPost a").click(function(){
        ZY.showArticle($(this).attr("href"));

        return false;
    });
    //关闭文章系那是
    $("#contentClose").click(function(){
        ZY.hideArticle();
    });

    //窗口大小改变的时候需要重新计算每个的高度和top值
    $(window).resize(function(){
        ZY.windowResize();
    });

    //屏幕旋转
    window.onorientationchange=function(){
        ZY.windowResize();
    };

    //初始化
    ZY.init();

});