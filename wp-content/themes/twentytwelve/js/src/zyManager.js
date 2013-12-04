/**
 * Created with JetBrains WebStorm.
 * User: ty
 * Date: 13-11-29
 * Time: 上午11:17
 * To change this template use File | Settings | File Templates.
 */
var ZY=(function(){
    var menu=$("#menu"),prevBtn=$("#prevPage"),nextBtn=$("#nextPage"),
        wrap=$("#wrap"),contentEl=$("#content"),mainContent=$("#mainContent");
    var currentPageNumber=1;
    var currentList=$("ul.productList:eq(0)"),currentListIndex=0;
    var productListPages=[Math.ceil($("ul.productList:eq(0) li").length/3),Math.ceil($("ul.productList:eq(1) li").length/3),Math.ceil($("ul.productList:eq(2) li").length/3)];
    var headerHeight=$("#topHeader").height(),menuHeight=menu.height();
    var indexEl=$("#index"),productEl=$("#product"),companyEl=$("#company"),joinUsEl=$("#joinUs");
    var indexTop=indexEl.offset().top,productTop=productEl.offset().top,
        companyTop=companyEl.offset().top,joinUsTop=joinUsEl.offset().top;

    var indexHeight=indexEl.innerHeight(),productHeight=productEl.innerHeight(),
        companyHeight=companyEl.innerHeight(),joinUsHeight=joinUsEl.innerHeight();

    var indexA= menu.find("li:eq(0) a"),productA= menu.find("li:eq(1) a"),
        companyA= menu.find("li:eq(2) a"),joinUsA= menu.find("li:eq(3) a");

    var iOS=navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false;

    return {

        /**
         * 启动函数，执行一些检测
         */
         init:function(){
            if(iOS){
                $("#listContainer").addClass("touchHscroll");
            }

            //加载后，激发一下scroll事件以更新页面的显示状态,有可能下拉到一半然后刷新，此时下拉条是在中间
            $(window).trigger("scroll");
        },

        /**
         * window滚动事件
         */
        windowScroll:function(){
            var sy=window.pageYOffset;
            if(sy>headerHeight){
                menu.addClass("active");
            }else{
                menu.removeClass("active");
            }

            if(sy>=indexTop&&sy<indexTop+indexHeight){
                indexA.addClass("active");
            }else{
                indexA.removeClass("active");
            }

            if(sy>=productTop&&sy<productTop+productHeight){
                productA.addClass("active");
            }else{
                productA.removeClass("active");
            }

            if(sy>=companyTop&&sy<companyTop+companyHeight){
                companyA.addClass("active");
            }else{
                companyA.removeClass("active");
            }

            if(sy>=joinUsTop&&sy<joinUsTop+joinUsHeight){
                joinUsA.addClass("active");
            }else{
                joinUsA.removeClass("active");
            }
        },

        /**
         * window放大缩小事件
         */
        windowResize:function(){
            indexTop=indexEl.offset().top;
            productTop=productEl.offset().top;
            companyTop=companyEl.offset().top;
            joinUsTop=joinUsEl.offset().top;

            indexHeight=indexEl.innerHeight();
            productHeight=productEl.innerHeight();
            companyHeight=companyEl.innerHeight();
            joinUsHeight=joinUsEl.innerHeight();

            $(window).triggerHandler("scroll");
        },


        /**
         * 滚动动画，主要用于菜单点击
         * @param {Object} target 需要滚动到的元素jquery对象
         */
        scrollToTarget:function(target){
            var top=target.offset().top;

            if(top!= undefined){
                TweenLite.killTweensOf(window);

                //加1是为了让滚动的事件设置菜单为active状态,如果不加1会显示成上一个菜单active
                TweenLite.to(window, 1, {scrollTo:{y:top+1, x:0}});
            }
        },

        /**
         * 产品分类目录点击事件
         * @param {Object} target 点击的产品分类a
         */
        productTypeClickHandler:function(target){
            var index=target.parent().index();
            var productList=$("#product .productList");
            $("#productType a").removeClass("active");
            target.addClass("active");
            productList.addClass("hidden").css("marginLeft",0);
            currentList=productList.eq(index);
            currentList.removeClass("hidden");
            currentListIndex=index;
            currentPageNumber=1;
            this.hidePageNav();
            this.showPageNav();
        },

        /**
         * 显示上一页下一页按钮
         */
        showPageNav:function(){
            if(productListPages[currentListIndex]!==1){
                if(1<=currentPageNumber&&currentPageNumber<productListPages[currentListIndex]){
                    nextBtn.removeClass("hidden");
                }

                if(currentPageNumber>1){
                    prevBtn.removeClass("hidden");
                }
            }
        },

        /**
         * 隐藏上一页下一页按钮
         */
        hidePageNav:function(){
            prevBtn.addClass("hidden");
            nextBtn.addClass("hidden");
        },

        /**
         * 上一页按钮点击事件
         */
        prevPageClickHandler:function(){
            TweenLite.to(currentList, 1, {css:{marginLeft:-(currentPageNumber-2)*100+"%"}});
            currentPageNumber-=1;
            nextBtn.removeClass("hidden");
            if(currentPageNumber===1){
                prevBtn.addClass("hidden");
            }
        },

        /**
         * 下一页按钮点击事件
         */
        nextPageClickHandler:function(){
            TweenLite.to(currentList, 1, {css:{marginLeft:-currentPageNumber*100+"%"}});
            currentPageNumber+=1;
            prevBtn.removeClass("hidden");
            if(currentPageNumber=productListPages[currentListIndex]){
                nextBtn.addClass("hidden");
            }
        },

        /**
         * 显示单篇文章
         * @param url
         */
        showArticle:function(url){
            wrap.removeClass("hidden");
            TweenLite.to(contentEl, 1, {css:{top:0}});
            mainContent.html("<div class='loadingSpinner'></div>");
            mainContent.load(url);
        },

        /**
         * 隐藏单篇文章
         */
        hideArticle:function(){
            wrap.addClass("hidden");
            mainContent.html("<div class='loadingSpinner'></div>");
            TweenLite.to(contentEl, 1, {css:{top:"-100%"},onComplete:function(){
                mainContent.html("");
            }});
        },

        /**
         * 浏览器版本过低
         */
        browseWarn:function(){
            $("#wrap").removeClass("hidden");
            alert("很抱歉，本站使用的一些HTML5特性，您的浏览器可能不支持，为了获得最佳浏览体验，建议您将浏览器升级到最新版本，" +
                "或选用其他兼容HTML5的浏览器，我们推荐Chrome浏览器和火狐浏览器。！");
        }
    }
})();
