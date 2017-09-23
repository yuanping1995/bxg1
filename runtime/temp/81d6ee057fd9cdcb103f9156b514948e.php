<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:65:"D:\phpStudy\WWW/./application/questionnaire\view\index\index.html";i:1505961359;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?php echo __APPROOT__; ?>/static/que/css/index.css">
    <script type="text/javascript" src="http://m.bxgogo.com/static/js/jquery.min.js" ></script>
    <script src="js/index.js"></script>
    <title>消费者购物偏好调查问卷</title>
</head>
<body>
    <!-- 整个页面 -->
    <div class="center">
        <!-- 标题 -->
        <h1>消费者购物偏好调查问卷</h1>
        <!-- 内容 -->
        <div class="content">
            <p>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp如今网络越来越发达，电子商务已经成为人们生活中重要的一部分。本地化电子商务平台已经成为消费者必选的趋势。我公司特做此次调查，占用您宝贵的几分钟填写以下问卷，本问卷调查结果仅用于学术用途，感谢您的参与！</p>
            <p>1.您的年龄是多少：</p>
                    <input type="radio" class="age" name="asda" value="A"/>
                        <span>A.18岁以下</span> 
                    <input type="radio" class="age" name="asda" value="B"/>
                        <span>B.18-25岁</span> 
                        <p></p>
                    <input type="radio" class="age"  name="asda" value="C"/>
                        <span>C.26-35岁</span>  
                    <input type="radio" class="age" name="asda" value="D"/>
                        <span>D.35岁以上</span>
            <p>2.性别：</p>
                    <input type="radio" name="Gender" value="A"/>
                        <span>A.男</span>   
                    <input type="radio" name="Gender" value="B"/>
                        <span>B.女 </span>



            <p>
               3.您经常网购吗：
            </p>

                <input type="radio" name="shopping" value="A"/>
                    <span>A.经常</span>
                <input type="radio" name="shopping" value="B"/>
                    <span>B.一般</span>     
                <p><input type="radio" name="shopping" value="C"/>
                    <span>C.偶尔</span>    
                <input type="radio" name="shopping" value="D"/>
                    <span>D.几乎不网购</span> 



            <p>4. 什么产品您会重复购买</p>

                    <input type="radio" name="repeat" value="A"/>
                        <span>服装类 </span>
                    <input type="radio" name="repeat" value="B"/>
                        <span>B. 家居</span> 
                   <p></p>
                    <input type="radio" name="repeat" value="C"/>
                        <span>C. 母婴</span>  
                 
                    <input type="radio" name="repeat" value="D"/>
                        <span>D.其他<input type="text" name="repeat" style="width:100px;" ></span>
            <p>5.您比较喜欢什么颜色</p>
                <input type="radio" name="colour" value="A"/>
                    <span>A.暖色调红、黄、橙</span>    
                <input type="radio" name="colour" value="B"/>
                    <span>B.冷色调青、蓝、紫</span> 
                <p><input type="radio" name="colour" value="C"/>
                    <span>C中间色系黑、白、灰</span>   
                <input type="radio" name="colour" value="D"/>
                    <span>D.其他<input type="text"name="colour" style="width:100px;"></span>
            <p>6.对于网购您关注的是什么</p>
                <input type="radio" name="follow" value="A"/>
                    <span>A.比较关注价格</span> 
                <input type="radio" name="follow" value="B"/>
                    <span>B.比较关注品质与样式</span>
                    <p></p>
                <input type="radio" name="follow" value="C"/>
                    <span>C物流速度</span>  
                <input type="radio" name="follow" value="D"/>
                    <span>D.其他<input type="text" name="follow" style="width:100px;"></span>
            <p>7.一般您比较喜欢在什么时间购物</p>
                <input type="radio" name="time" value="A"/>
                    <span>A.  白天的工作时间</span>  
                <input type="radio" name="time" value="B"/>
                    <span>B.  晚上7、8点以后</span> 
                <p><input type="radio" name="time" value="C"/>
                    <span>C  中午时间</span>  
                <input type="radio" name="time" value="D"/>
                <span>D.其他时间</span>  </p>
            <p>8.网购你能接受的价格在什么范围</p>
                <input type="radio" name="Price" value="A"/>
                    <span>A.  100以内</span>    
                <input type="radio" name="Price" value="B"/>
                    <span>B.  100-300之间</span>
                <p><input type="radio" name="Price" value="C"/>    <span>C 300-500之间</span>
                <input type="radio" name="Price" value="D"/>
                    <span>D.500以上</span> </p>
            <p>9.平时您喜欢关注哪方面的信息</p>
                <input type="radio" name="infor" value="A"/>
                    <span>A. 八卦方面</span> 
                <input type="radio" name="infor" value="B"/>
                    <span>B. 正能量的文章</span> 
                <p><input type="radio" name="infor" value="C"/>
                    <span>C .新闻方面</span>  
                <input type="radio" name="infor" value="D"/>
                    <span>D.情感类 </span></p> 
                 <p><input type="radio" name="infor" value="E"/>   <span>E .美容、服装搭配</span>
                <input type="radio" name="infor" value="F"/>
                    <span>F.健康、养生类</span> </p>
            <p>10.假如你不喜欢网络购物，主要原因是（可多选）</p>
                <input type="radio" name="mode" value="A"/>
                    <span>A.  网上支付太麻烦</span>
                <input type="radio" name="mode" value="B"/>
                    <span>B.  不信任网站，怕受骗</span>
                <p><input type="radio" name="mode" value="C"/>     <span>C 担心商品品质有问题</span>
                <input type="radio" name="mode" value="D"/>
                    <span>D.担心商品配送有问题</span> </p>
                 <p><input type="radio" name="mode" value="E"/>
                    <span>E. 不了解如何购买 </span>  
                <input type="radio" class="asd" name="mode" value="F"/>
                    <span>F.  觉得售后麻烦</span>  </p>
                 <p  class="last"><input type="radio" name="mode" value="G"/>
                        <span>G.其他<input type="text" class="mode" style="width:80px;"></span>
                  </p>
             <input id="asd" type="button" value="做完了，我要去注册啦">

        </div>
    </div>
</body>
</html>
<script>
    $("#asd").click(function () {
      //   alert($("input[type='asda']:checked").val());
            age= $("input[name='asda']:checked").val();
            Gender= $("input[name='Gender']:checked").val();
            shopping= $("input[name='shopping']:checked").val();
            repeat= $("input[name='repeat']:checked").val();
            colour= $("input[name='colour']:checked").val();
            follow= $("input[name='follow']:checked").val();
            time= $("input[name='time']:checked").val();
            Price= $("input[name='Price']:checked").val();
            infor= $("input[name='infor']:checked").val();
        mode= $("input[name='mode']:checked").val();
            $.ajax({
                url:"http://127.0.0.1/index.php/Questionnaire/index/addque",
                type:"post",
                data:{age:age,Gender:Gender,shopping:shopping,repeat:repeat,colour:colour,follow:follow,time:time,Price:Price,infor:infor,mode:mode},
                success:function(data){
                    window.location.href="http://login.bxgogo.com?qer=1" ;
                },
                error:function(e){
                    alert("请你填写完整！谢谢诶合作！");
                    window.clearInterval(timer);
                }
            });
        }
    )

</script>