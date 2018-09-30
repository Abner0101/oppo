<?php
namespace app\home\controller;

class Wechat extends \think\Controller 
{
	public function sendMsg($openid, $str)
	{
		$appid = "wxc0f8e88d15289fd7";
		$secret = "98adc14c4e2a7f03d5ce94fc6e6eef7d";
		$info = cache('access_token');
		if ( empty($info) )
		{
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
			$info = get($url);
			cache("access_token", $info, 7200);
		} 
		
		$info = json_decode($info, true);
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$info['access_token']}";

		$data = [
			'touser' => "{$openid}",
			'msgtype' => "text",
			'text' => [
				"content"=> "{$str}"
			]
		];

		$data = [
			'touser' => "{$openid}",
			'msgtype' => "image",
			'image' => [
				"media_id"=> "{$str}"
			]
		];

		$data = [
			'touser' => "{$openid}",
			'msgtype' => "voice",
			'voice' => [
				"media_id"=> "{$str}"
			]
		];

		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		$ret = post($url, $data);
		var_dump($ret);
	}

	public function up()
	{
		$file = request()->file('img');
	    // 移动到框架应用根目录/public/uploads/ 目录下
	    $img_src = "";
	    if($file){
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	        if($info){
	            // 成功上传后 获取上传信息
	            // 输出 jpg
	            $img_src = ROOT_PATH . 'public/uploads/'.$info->getSaveName();

	        }else{
	            // 上传失败获取错误信息
	            $this->error($file->getError());
	        }
	    }

	    $appid = "wxc0f8e88d15289fd7";
		$secret = "98adc14c4e2a7f03d5ce94fc6e6eef7d";
		$info = cache('access_token');
		if ( empty($info) )
		{
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
			$info = get($url);
			cache("access_token", $info, 7200);
		} 
		
		$info = json_decode($info, true);
		$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$info['access_token']}&type=image";

		$data = array(
			"media" => new \CURLFile($img_src)
		);

		$media = post($url, $data);
		$media = json_decode($media, true);

		//发消息
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$info['access_token']}";

		$data = [
			'touser' => "o91xe1UfvFuepVHpRVHDHWlawlJc",
			'msgtype' => "image",
			'image' => [
				"media_id"=> "{$media['media_id']}"
			]
		];

		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		$ret = post($url, $data);
		var_dump($data);
	}

	public function upvoice()
	{
		$file = request()->file('img');
	    // 移动到框架应用根目录/public/uploads/ 目录下
	    $img_src = "";
	    if($file){
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	        if($info){
	            // 成功上传后 获取上传信息
	            // 输出 jpg
	            $img_src = ROOT_PATH . 'public/uploads'.$info->getSaveName();

	        }else{
	            // 上传失败获取错误信息
	            $this->error($file->getError());
	        }
	    }

	    $appid = "wxc0f8e88d15289fd7";
		$secret = "98adc14c4e2a7f03d5ce94fc6e6eef7d";
		$info = cache('access_token');
		if ( empty($info) )
		{
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
			$info = get($url);
			cache("access_token", $info, 7200);
		} 
		
		$info = json_decode($info, true);
		$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$info['access_token']}&type=voice";

		$data = array(
			"media" => new \CURLFile($img_src)
		);

		$media = post($url, $data);
		$media = json_decode($media, true);

		//发消息
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$info['access_token']}";

		$data = [
			'touser' => "o91xe1UfvFuepVHpRVHDHWlawlJc",
			'msgtype' => "voice",
			'voice' => [
				"media_id"=> "{$media['media_id']}"
			]
		];

		$data = [
			'touser' => "o91xe1UfvFuepVHpRVHDHWlawlJc",
			'msgtype' => "video",
			"video" =>
			    [
			      "media_id" => "{$media['media_id']}",
			      "thumb_media_id"=>"YceBQ5CggFI3x1DdUm81yDLDJmBMPb6DxGjzPGBBg361QmEEevIzL00K_LvnRLLk",
			      "title"=>"TITLE",
			      "description"=>"DESCRIPTION"
			    ]
		];

		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		$ret = post($url, $data);
		var_dump($data);
	}

	public function send()
	{
		
		// $this->assign("token", $info['access_token']);
		return view('send');
	}

	public function response()
	{
		// //获得参数 signature nonce token timestamp echostr
		$nonce     = $_GET['nonce'];
		$token     = 'allen';
		$timestamp = $_GET['timestamp'];
		$echostr   = isset($_GET['echostr'])?$_GET['echostr']: false;
		$signature = $_GET['signature'];
		//形成数组，然后按字典序排序
		$array = array();
		$array = array($nonce, $timestamp, $token);
		sort($array);
		//拼接成字符串,sha1加密 ，然后与signature进行校验
		$str = sha1( implode( $array ) );
		if( $str == $signature && $echostr ){
			$ret = file_put_contents('./log.txt', json_encode($_GET), FILE_APPEND);
			//第一次接入weixin api接口的时候,验证token
			echo  $echostr;
			exit;
		} else {
			//被动消息回复， 文档位置---消息管理->被动回复用户消息

			//接收的普通消息（即用户发送的消息）， 文档位置----->消息管理--->接收普通消息

			//1.获取到微信推送过来post数据（xml格式）
			$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
			$ret = file_put_contents('./log.txt', $postArr, FILE_APPEND);
			//2.处理消息类型，并设置回复类型和内容
			/*<xml>
			<ToUserName><![CDATA[toUser]]></ToUserName>
			<FromUserName><![CDATA[FromUser]]></FromUserName>
			<CreateTime>123456789</CreateTime>
			<MsgType><![CDATA[event]]></MsgType>
			<Event><![CDATA[subscribe]]></Event>
			</xml>*/
			$postObj = simplexml_load_string( $postArr );
			//$postObj->ToUserName = '';
			//$postObj->FromUserName = '';
			//$postObj->CreateTime = '';
			//$postObj->MsgType = '';
			//$postObj->Event = '';
			// gh_e79a177814ed
			//判断该数据包是否是订阅的事件推送
			if( strtolower( $postObj->MsgType) == 'event'){ //事件推送型消息
				//如果是关注 subscribe 事件
				if( strtolower($postObj->Event == 'subscribe') ){ //事件有：关注、取消关注、点击菜单等
					//回复用户消息(纯文本格式)
					$toUser   = $postObj->FromUserName;
					$fromUser = $postObj->ToUserName;
					$time     = time();
					$msgType  =  'text';
					$content  = '欢迎关注';
					$template = "<xml>
									<ToUserName><![CDATA[%s]]></ToUserName>
									<FromUserName><![CDATA[%s]]></FromUserName>
									<CreateTime>%s</CreateTime>
									<MsgType><![CDATA[%s]]></MsgType>
									<Content><![CDATA[%s]]></Content>
									</xml>";
					$info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
					echo $info;
					/*<xml>
					<ToUserName><![CDATA[toUser]]></ToUserName>
					<FromUserName><![CDATA[fromUser]]></FromUserName>
					<CreateTime>12345678</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[你好]]></Content>
					</xml>*/
				}
			} else if ( strtolower( $postObj->MsgType) == 'text' ) { //判断消息类型，有：文本、语音、视频、图片、地理位置等
				$toUser   = $postObj->FromUserName;
				$fromUser = $postObj->ToUserName;
				$time     = time();
				$msgType  =  'text';
				//$content  = '欢迎关注我们的微信公众账号'.$postObj->FromUserName.'-'.$postObj->ToUserName;
				if ( $postObj->Content == "嘻嘻" )
				{
					$content = "哈哈";
				}
				elseif ( $postObj->Content == "鸡腿" ) {
					$content = "你想给我加鸡腿吗？";
				}
				elseif ( $postObj->Content == "恭喜发财" ) {
					$content = "快发红包来";
				}
				elseif ( $postObj->Content == "哇" ) {
					$content = "不要迷恋哥";
				}
				else
				{
					$content = "我还不够智能";
				}
				//被动回复消息的格式和类型见官方文档，文档位置---消息管理->被动回复用户消息
				$template = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<Content><![CDATA[%s]]></Content>
							</xml>";
				$info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
				echo $info;
			}
			else if ( strtolower( $postObj->MsgType) == 'image' ) { //
			//HB-qZKJkHUfWOnfLmgsl1sTger2vOWtNbChiWU3WbEUBTLAR_BVGFl7_jlF7dZFT
				$toUser   = $postObj->FromUserName;
				$fromUser = $postObj->ToUserName;
				$time     = time();
				$msgType  =  'image';
				//$content  = '欢迎关注我们的微信公众账号'.$postObj->FromUserName.'-'.$postObj->ToUserName;
				$content = "jbm8-epyjFbc6zLpn5sBlPo2iwV656_hiGt1yTrXWazAIBZz5h6pFlJcnQtDOrmj";
				//被动回复消息的格式和类型见官方文档，文档位置---消息管理->被动回复用户消息
				$template = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<Image><MediaId><![CDATA[%s]]></MediaId>
							</xml>";
				$info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
				echo $info;
			}
		}
	
   }


}