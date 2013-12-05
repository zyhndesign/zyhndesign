/**
 * Created with JetBrains WebStorm.
 * User: ty
 * Date: 13-11-29
 * Time: 上午11:17
 * To change this template use File | Settings | File Templates.
 */
var ZY=(function(){
    var menu=null,prevBtn=null,nextBtn=null,body=null,
        wrap=null,contentEl=null,mainContent=null;
    var currentPageNumber=1;
    var currentList=null,currentListIndex=0;
    var productListPages=[];
    var headerHeight=0;
    var indexEl=null,productEl=null,companyEl=null,joinUsEl=null;
    var indexTop=0,productTop=0,companyTop=0,joinUsTop=0;

    var indexHeight=0,productHeight=0,companyHeight=0,joinUsHeight=0;

    var indexA= null,productA= null,companyA= null,joinUsA= null;

    var iOS=navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false;

    var loadingHtml="<div class='loadingSpinner'></div>";

    return {

        /**
         * 启动函数，执行一些检测
         */
         init:function(){
            body=$("body");
            menu=$("#menu");
            prevBtn=$("#prevPage");
            nextBtn=$("#nextPage");
            wrap=$("#wrap");
            contentEl=$("#content");
            mainContent=$("#mainContent");

            this.hideWrap();

            currentList=$("ul.productList:eq(0)");
            productListPages=[Math.ceil($("ul.productList:eq(0) li").length/3),Math.ceil($("ul.productList:eq(1) li").length/3),Math.ceil($("ul.productList:eq(2) li").length/3)];

            indexEl=$("#index");
            productEl=$("#product");
            companyEl=$("#company");
            joinUsEl=$("#joinUs");

            indexA= menu.find("li:eq(0) a");
            productA= menu.find("li:eq(1) a");
            companyA= menu.find("li:eq(2) a");
            joinUsA= menu.find("li:eq(3) a");

            indexTop=indexEl.offset().top;
            productTop=productEl.offset().top;
            companyTop=companyEl.offset().top;
            joinUsTop=joinUsEl.offset().top;

            headerHeight=$("#topHeader").outerHeight();
            indexHeight=indexEl.outerHeight();
            productHeight=productEl.outerHeight();
            companyHeight=companyEl.outerHeight();
            joinUsHeight=joinUsEl.outerHeight();

            if(iOS){
                $("#listContainer").addClass("touchHscroll");
            }

            //窗口滚动事件,需要放到图片加载完后绑定，因为图片没加载完是无法获取高度的
            $(window).scroll(function(){
                ZY.windowScroll();
            });

            //加载后，激发一下scroll事件以更新页面的显示状态,有可能下拉到一半然后刷新，此时下拉条是在中间
            $(window).trigger("scroll");
        },

        /**
         * 显示wrap层
         * @param {Boolean} showLoading 是否显示loading状态
         */
        showWrap:function(showLoading){
            if(showLoading===true){
                wrap.html(loadingHtml);
            }

            wrap.removeClass("hidden");
        },

        /**
         * 隐藏wrap层
         */
        hideWrap:function(){
            wrap.html("").addClass("hidden");
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
            this.showWrap();
            body.addClass("noscroll");
            TweenLite.to(contentEl, 1, {css:{top:0}});
            mainContent.html(loadingHtml);
            mainContent.load(url);
        },

        /**
         * 隐藏单篇文章
         */
        hideArticle:function(){
            this.hideWrap();
            body.removeClass("noscroll");
            mainContent.html(loadingHtml);
            TweenLite.to(contentEl, 1, {css:{top:"-100%"},onComplete:function(){
                mainContent.html("");
            }});
        }
    }
})();
