<?php if(!defined("HDPHP_PATH"))exit;C("SHOW_NOTICE",FALSE);?>		<link href="http://localhost/proxy/./Proxy/App/Index/Tpl/Public/css/index.css" rel="stylesheet" type="text/css">
		<script type="text/javascript">
			function SetIndex(obj){
				try{
					obj.style.behavior='window.location(#default#homepage)';
					obj.setHomePage(window.location);
				}catch(e){
					if(window.netscape) {
						try {
							netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
						}catch (e) {
							alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
						}
							var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
							prefs.setCharPref('browser.startup.homepage',window.location);
					}else{
						alert("您的浏览器不支持，请按照下面步骤操作：1.打开浏览器设置。2.点击设置网页。3.输入：" + window.location + "点击确定。");
					}
				}
			}
		</script>
	</head>
	<body>
		<!-- 头部开始 -->
		<div class="header">
			<!-- 注册与登录信息栏开始 -->
			<div class="top-bar">
				<p class="left">
					<a href="Javascript:SetIndex(this)">设为首页</a> | <a href="Javascript:AddFavorite(WEB, 'YY智能采集')">加入收藏</a>
				</p>
				<p class="right">
					<?php if($_SESSION['username'] && $_SESSION['uid']){?>
						您好，<?php echo $_SESSION['username'];?>！欢迎来到YY影视智能采集！<a href="<?php echo U('Index/Login/out');?>" id="exit" title="退出">退出</a> | <a href="<?php echo U('Passport/Index/index');?>" title="个人中心">个人中心</a>
					<?php  }else{ ?>
						您好，欢迎来到YY影视智能采集！[<a href="#" title="登录" class="login">登录</a>] [<a href="#" title="免费注册" class="register">免费注册</a>]
					<?php }?>
					 | <a href="" title="新手入门">新手入门</a> <a href="#" title="偏好设置">偏好设置</a> <a href="<?php echo C("WEIBO");?>" title="微博">微博</a>
				</p>
			</div>
			<!-- 注册与登录信息栏结束 -->
			<?php if(!$_SESSION['username'] && !$_SESSION['uid']){?>
				<!-- 注册与登录弹出框开始 -->
				<!-- 注册框开始 -->
				<div id="register" class="hidden">
					<div class="reg-title">
						<p>
							欢迎注册YY影视智能采集
						</p>
						<a href="" title="关闭" class="close-window"></a>
					</div>
					<div id="reg-wrap">
						<div class="reg-left">
							<ul>
								<li><span>账号注册</span></li>
							</ul>
							<ul class="reg-l-bottom">
								<li>已有账号，<a href="" id="login-now">马上登录</a></li>
								<li><a href="<?php echo U('Passport/Qqlogin/login');?>" class="qq_login" title="使用QQ登录"></a></li>
							</ul>
						</div>
						<div class="reg-right">
							<!-- 注册表单开始 -->
							<form action="<?php echo U('Index/Register/register');?>" method="post" name="register">
								<ul>
									<li>
										<label for="reg-uname">用户名</label>
										<input type="text" name="username" id="reg-uname">
										<span>2-14个字符：字母、数字或中文</span> </li>
									<li>
										<label for="reg-pwd">密码</label>
										<input type="password" name="pwd" id="reg-pwd">
										<span>6-20个字符:字母、数字或下划线 _</span> </li>
									<li>
										<label for="reg-pwded">确认密码</label>
										<input type="password" name="pwded" id="reg-pwded">
										<span>请再次输入密码</span> </li>
									<li>
										<label for="reg-union">频道ID</label>
										<input type="text" name="userunion" id="reg-union">
										<span>2-10位字符：数字</span> </li>
									<li>
										<label for="reg-verify">验证码</label>
										<input type="text" name="verify" id="reg-verify">
										<img src="<?php echo U('Index/Register/code');?>" width="99" height="35" alt="验证码" id="verify-img">
										<span>请输入图中的字母或数字，不区分大小写</span>
									</li>
									<li class="submit">
										<input type="submit" value="立即注册">
									</li>
								</ul>
							</form>
							<!-- 注册表单结束 -->
						</div>
					</div>
				</div>
				<!-- 注册框结束 -->
				<!-- 登录框开始 -->
				<div id="login" class="hidden">
					<div class="login-title">
						<p>欢迎您登录YY影视智能采集</p>
						<a href="" title="关闭" class="close-window"></a>
					</div>
					<div class="login-form">
						<span id="login-msg"></span>
						<!-- 登录FORM -->
						<form action="<?php echo U('Index/Login/login');?>" method="post" name="login">
							<ul>
								<li>
									<label for="login-acc">账号</label>
									<input type="text" name="username" class="input" id="login-acc">
								</li>
								<li>
									<label for="login-pwd">密码</label>
									<input type="password" name="pwd" class="input" id="login-pwd">
								</li>
								<li class="login-auto">
									<label for="auto-login">
										<input type="checkbox" checked="checked" name="auto" id="auto-login">下一次自动登录
									</label>
									<a href="" id="regis-now">注册新账号</a>
								</li>
								<li>
									<input type="submit" value="" id="login-btn">
								</li>
								<li><a href="<?php echo U('Passport/Qqlogin/login');?>" class="qq_login" title="使用QQ登录"></a></li>
							</ul>
						</form>
						<!-- 登录FORM结束 -->
					</div>
				</div>
				<!-- 登录框结束 -->
				<!--背景遮罩-->
				<div id="background" class="hidden">
				</div>
				<!--背景遮罩结束-->
				<!-- 注册与登录信息栏结束 -->
			<?php }?>