<?php
$mainItems= wp_get_nav_menu_items(10);//main菜单下的所有items

$productId=2;//

$productCategories=get_categories(array("parent"=>$productId,"hide_empty"=>false,'orderby'=>'id'));

$joinUsId=6;//

$joinUsCategories=get_categories(array("parent"=>$joinUsId,"hide_empty"=>false,'orderby'=>'id'));
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="中意工业设计（湖南）有限责任公司网站" />
    <meta name="keywords" content="中意工业设计，中意工业设计（湖南），设计" />
    <title>中意工业设计（湖南）</title>
    <link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/app/index.min.css">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/app/favicon.png"
          mce_href="<?php echo get_template_directory_uri(); ?>/images/app/favicon.png" type="image/x-png">


    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/lib/modernizr.js"></script>
    <script type="text/javascript">
        //加载资源
        Modernizr.load([
            {
                test:Modernizr.rgba &&
                    Modernizr.borderradius &&
                    Modernizr.boxshadow &&
                    Modernizr.textshadow &&
                    Modernizr.opacity &&
                    Modernizr.cssanimations &&
                    Modernizr.csscolumns &&
                    Modernizr.csstransitions &&
                    Modernizr.generatedcontent &&
                    Modernizr.video &&
                    Modernizr.audio,
                nope:["<?php echo get_template_directory_uri(); ?>/js/lib/jquery-1.10.2.min.js",
                    "<?php echo get_template_directory_uri(); ?>/js/src/zyManager.js"],
                yep:[
                    "<?php echo get_template_directory_uri(); ?>/js/lib/jquery-1.10.2.min.js",
                    "<?php echo plugins_url(); ?>/simple-responsive-slider/assets/js/responsiveslides.min.js",
                    "<?php echo get_template_directory_uri(); ?>/js/lib/TweenMax.min.js",
                    "<?php echo get_template_directory_uri(); ?>/js/lib/ScrollToPlugin.min.js",
                    "<?php echo get_template_directory_uri(); ?>/js/build/index.min.js"
                ],
                callback:function(url,testResult){
                    if(testResult!==true&&url==="<?php echo get_template_directory_uri(); ?>/js/src/zyManager.js"){

                        $("body").append("<div class='popOut'>很抱歉，本站使用的一些HTML5特性，您的浏览器可能不支持，为了获得最佳浏览体验，建议您将浏览器升级到最新版本，" +
                            "或选用其他兼容HTML5的浏览器，我们推荐Chrome浏览器和火狐浏览器。！</div>");
                    }else{

                        //初始化高度等
                        if(url==="<?php echo get_template_directory_uri(); ?>/js/build/index.min.js"){
                            <?php
                                global $simple_responsive_slider;

                                $simple_responsive_slider->dynamics_scripts();
                           ?>
                            if(document.readyState==="complete"){
                                ZY.init();
                            }else{
                                window.onload=function(){
                                    ZY.init();
                                }
                            }
                        }
                    }
                }
            }
        ]);
    </script>

    <!--执行一次加载头部文件，有些插件可能需要-->
    <?php /*wp_head(); */?>
</head>
<body>
<!--菜单-->
<nav id="menu">
    <h1><a href="#" id="logo" class="logo">中意工业设计（湖南）</a></h1>
    <ul>
        <?php
        foreach ($mainItems as $key => $menu_item ) {
            ?>
            <li><a href="<?php echo $menu_item->url; ?>"><?php echo $menu_item->title; ?></a></li>
            <?php
        }
        ?>
    </ul>
</nav>
<!--最新文章-->
<header class="topHeader" id="topHeader">
    <?php

    $recentPosts=wp_get_recent_posts(array("numberposts"=>1,'category' => $productId),OBJECT);


    foreach($recentPosts as $post){
        $postId=$post->ID;
        if($background=get_post_meta($postId,"zy_background",true)){
            $background=json_decode($background,true);
            $background_src=$background["filepath"];
        }else{
            $background_src=get_template_directory_uri()."/images/app/defaultArticleBg.jpg";
        }
    ?>

        <img src="<?php echo $background_src ?>">
        <div class="topPost">
            <h2><a href="<?php echo get_permalink($postId); ?>" target="_blank"><?php echo $post->post_title ?></a></h2>
            <p class="date"><?php echo mysql2date("Y-m-d", $post->post_date); ?></p>
        </div>

    <?php
    }
    ?>

    <span class="down" id="down">向下</span>
</header>

<!--首页-->
<section id="index" class="index">
    <div class="indexIntroduce">
        <h2>首页介绍.</h2>
        <p>公司主要以设计创新为驱动， 在“互动影像”、“智能家居”、“数字阅读”三大互联网应用领域打造具有优秀用户体验和市场价值的产品及服务。</p>
    </div>
    <ul>
        <li>
            <h3 class="imageIntroduce">互动影像</h3>
            <p>主要应用于会展、博物馆、旅游景区，将高质量的影像作品与传感器技术相结合，提供给访客在观看影像的同时与影像产生互动，提高用户体验。</p>
        </li>
        <li>
            <h3 class="homeIntroduce">智能家居</h3>
            <p>围绕现代家居的生活方式，通过优秀的工业设计、交互设计、嵌入式技术、互联网和物联网技术，打造个性化、智能化以及移动家居产品和服务。</p>
        </li>
        <li>
            <h3 class="readIntroduce">数字阅读</h3>
            <p>超越传统的图文读本、电子书的形式，整合视频、动画、环景以及3D模型等富交互元素，打造具有高度互动性的，沉浸式的阅读体验，
                同时利用响应式、混合型APP的方式对各类显示终端进行优化，形成完整的跨屏浏览器解决方案。</p>
        </li>
    </ul>
</section>

<!--产品介绍-->
<section id="product" class="product">
    <div class="productIntroduce">
        <h2>产品.</h2>
        <ul class="productType" id="productType">

            <?php
            foreach ($productCategories as $key=>$category) {
                if($key===0){
                    ?>
                    <li><a href="<?php echo get_category_link( $category->term_id ); ?>" class="active"><?php echo $category->name; ?></a></li>
                    <?php
                }else{
                    ?>
                    <li><a href="<?php echo get_category_link( $category->term_id ); ?>"><?php echo $category->name; ?></a></li>
                    <?php
                }
            }
            ?>

        </ul>
    </div>
    <a class="prevPage hidden" id="prevPage">上一页</a>
    <a class="nextPage hidden" id="nextPage">下一页</a>
    <div class="listContainer" id="listContainer">
        <?php
            foreach($productCategories as $key=>$value){
                $posts=get_posts(array('posts_per_page' => -1,  'category' => $value->term_id ));
                if($key==0){
                   ?>
                    <ul class="productList">
                        <?php

                        foreach($posts as $post){
                            $post_id=$post->ID;
                            setup_postdata($post);
                            if(has_post_thumbnail($post_id)){
                                $thumbnail_id=get_post_thumbnail_id($post_id);
                                $showDir= wp_get_attachment_image_src($thumbnail_id,"post-thumbnail");
                                $showDir=$showDir[0];
                            }else{
                                $showDir=get_template_directory_uri()."/images/app/defaultThumb.jpg";
                            }
                            ?>
                            <li class="productArticle">
                                <a href="<?php the_permalink(); ?>" target="_blank">
                                    <div class="thumb">
                                        <img src="<?php echo $showDir; ?>">
                                        <div>遮盖层</div>
                                        <span>查看</span>
                                    </div>
                                    <div class="abstract">
                                        <h3><?php the_title(); ?></h3>
                                        <p><?php echo get_the_date("Y-m-d"); ?></p>
                                    </div>
                                </a>
                            </li>
                        <?php
                        }
                        wp_reset_postdata();
                        ?>
                    </ul>
                   <?php
                }else{
                   ?>
                    <ul class="productList hidden">
                        <?php
                        foreach($posts as $post){
                            $post_id=$post->ID;
                            if(has_post_thumbnail($post_id)){
                                $thumbnail_id=get_post_thumbnail_id($post_id);
                                $showDir= wp_get_attachment_image_src($thumbnail_id,"post-thumbnail");
                                $showDir=$showDir[0];
                            }else{
                                $showDir=get_template_directory_uri()."/images/app/defaultThumb.jpg";
                            }
                            ?>
                            <li class="productArticle">
                                <a href="<?php echo get_permalink($post_id); ?>" target="_blank">
                                    <div class="thumb">
                                        <img src="<?php echo $showDir; ?>">
                                        <div>遮盖层</div>
                                        <span>查看</span>
                                    </div>
                                    <div class="abstract">
                                        <h3><?php echo $post->post_title; ?></h3>
                                        <p><?php echo mysql2date("Y-m-d", $post->post_date); ?></p>
                                    </div>
                                </a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                   <?php
                }
            }
        ?>
    </div>
</section>

<!--公司介绍-->
<section id="company" class="company">
    <div class="companyIntroduce">
        <h2>公司介绍.</h2>
        <p>中意工业设计（湖南）有限责任公司成立于2013年4月，致力于将互联网技术应用于数字展示、智能家居以及数字阅读领域，打造具有影响力的互联网产品。</p>
    </div>

    <?php if ( function_exists( 'show_simpleresponsiveslider' ) ) show_simpleresponsiveslider();?>
</section>

<!--加入我们-->
<section id="joinUs" class="joinUs">
    <h2>加入我们.</h2>
    <?php
        foreach($joinUsCategories as $value){
            ?>
            <div class="inviteTypes">
                <h3><?php echo $value->name; ?></h3>
                <ul>
                    <?php
                    $posts=get_posts(array('posts_per_page' => -1,  'category' => $value->term_id ));
                    foreach($posts as $post){
                    ?>
                        <li><a href="<?php echo get_permalink($post->ID); ?>" target="_blank"><?php echo $post->post_title; ?></a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
    ?>
</section>

<footer>
    <h3>联系我们</h3>
    <div class="address">
        <p>邮箱：zyhndesign@zyhndesign.com</p>
        <p>地址：湖南省长沙市岳麓大道233号湖南科技大厦1楼</p>
        <p class="copyright">Copyright &copy; 2013 中意工业设计（湖南）有限责任公司 版权所有 湘ICP备12014319号-5</p>
    </div>
</footer>
<div id="wrap" class="wrap"><div class='loadingSpinner'></div></div>
<article id="content" class="content">
    <div class="contentClose" id="contentClose">
        <span></span>
    </div>
    <section class="mainContent" id="mainContent">
        <!--<div class="loadingSpinner"></div>-->
    </section>
</article>
</body>
</html>