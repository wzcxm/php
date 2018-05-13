var __reflect=this&&this.__reflect||function(e,t,n){e.__class__=t,n?n.push(t):n=[t],e.__types__=e.__types__?n.concat(e.__types__):n},__extends=this&&this.__extends||function(e,t){function n(){this.constructor=e}for(var i in t)t.hasOwnProperty(i)&&(e[i]=t[i]);n.prototype=t.prototype,e.prototype=new n},__awaiter=this&&this.__awaiter||function(e,t,n,i){return new(n||(n=Promise))(function(r,o){function s(e){try{h(i.next(e))}catch(t){o(t)}}function a(e){try{h(i["throw"](e))}catch(t){o(t)}}function h(e){e.done?r(e.value):new n(function(t){t(e.value)}).then(s,a)}h((i=i.apply(e,t||[])).next())})},__generator=this&&this.__generator||function(e,t){function n(e){return function(t){return i([e,t])}}function i(n){if(r)throw new TypeError("Generator is already executing.");for(;h;)try{if(r=1,o&&(s=o[2&n[0]?"return":n[0]?"throw":"next"])&&!(s=s.call(o,n[1])).done)return s;switch(o=0,s&&(n=[0,s.value]),n[0]){case 0:case 1:s=n;break;case 4:return h.label++,{value:n[1],done:!1};case 5:h.label++,o=n[1],n=[0];continue;case 7:n=h.ops.pop(),h.trys.pop();continue;default:if(s=h.trys,!(s=s.length>0&&s[s.length-1])&&(6===n[0]||2===n[0])){h=0;continue}if(3===n[0]&&(!s||n[1]>s[0]&&n[1]<s[3])){h.label=n[1];break}if(6===n[0]&&h.label<s[1]){h.label=s[1],s=n;break}if(s&&h.label<s[2]){h.label=s[2],h.ops.push(n);break}s[2]&&h.ops.pop(),h.trys.pop();continue}n=t.call(e,h)}catch(i){n=[6,i],o=0}finally{r=s=0}if(5&n[0])throw n[1];return{value:n[0]?n[1]:void 0,done:!0}}var r,o,s,a,h={label:0,sent:function(){if(1&s[0])throw s[1];return s[1]},trys:[],ops:[]};return a={next:n(0),"throw":n(1),"return":n(2)},"function"==typeof Symbol&&(a[Symbol.iterator]=function(){return this}),a},HttpManager=function(){function e(){this.request=new egret.HttpRequest}return e.create=function(){return new e},e.prototype.Get=function(e,t,n,i){void 0===i&&(i=null),this.request.responseType=egret.HttpResponseType.TEXT,this.request.open(e,egret.HttpMethod.GET),this.request.send(),this.request.addEventListener(egret.Event.COMPLETE,function(e){var n=e.currentTarget;t.call(i,n.response)},i),this.request.addEventListener(egret.IOErrorEvent.IO_ERROR,n,i)},e.prototype.Post=function(e,t,n,i,r){void 0===i&&(i=null),void 0===r&&(r=""),this.request.responseType=egret.HttpResponseType.TEXT,this.request.open(e,egret.HttpMethod.POST),this.request.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),this.request.send(r),this.request.addEventListener(egret.Event.COMPLETE,function(e){var n=e.currentTarget;t.call(i,n.response)},i),this.request.addEventListener(egret.IOErrorEvent.IO_ERROR,n,i)},e}();__reflect(HttpManager.prototype,"HttpManager");var AssetAdapter=function(){function e(){}return e.prototype.getAsset=function(e,t,n){function i(i){t.call(n,i,e)}if(RES.hasRes(e)){var r=RES.getRes(e);r?i(r):RES.getResAsync(e,i,this)}else RES.getResByUrl(e,i,this,RES.ResourceItem.TYPE_IMAGE)},e}();__reflect(AssetAdapter.prototype,"AssetAdapter",["eui.IAssetAdapter"]);var Main=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return __extends(t,e),t.prototype.createChildren=function(){e.prototype.createChildren.call(this),egret.lifecycle.addLifecycleListener(function(e){}),egret.lifecycle.onPause=function(){egret.ticker.pause()},egret.lifecycle.onResume=function(){egret.ticker.resume()};var t=new AssetAdapter;egret.registerImplementation("eui.IAssetAdapter",t),egret.registerImplementation("eui.IThemeAdapter",new ThemeAdapter),this.runGame()["catch"](function(e){console.log(e)})},t.prototype.runGame=function(){return __awaiter(this,void 0,void 0,function(){var e,t;return __generator(this,function(n){switch(n.label){case 0:return[4,this.loadResource()];case 1:return n.sent(),this.createGameScene(),[4,RES.getResAsync("description_json")];case 2:return e=n.sent(),this.startAnimation(e),[4,platform.login()];case 3:return n.sent(),[4,platform.getUserInfo()];case 4:return t=n.sent(),console.log(t),[2]}})})},t.prototype.loadResource=function(){return __awaiter(this,void 0,void 0,function(){var e,t;return __generator(this,function(n){switch(n.label){case 0:return n.trys.push([0,4,,5]),e=new LoadingUI,this.stage.addChild(e),[4,RES.loadConfig("resource/default.res.json","resource/")];case 1:return n.sent(),[4,this.loadTheme()];case 2:return n.sent(),[4,RES.loadGroup("preload",0,e)];case 3:return n.sent(),this.stage.removeChild(e),[3,5];case 4:return t=n.sent(),console.error(t),[3,5];case 5:return[2]}})})},t.prototype.loadTheme=function(){var e=this;return new Promise(function(t,n){var i=new eui.Theme("resource/default.thm.json",e.stage);i.addEventListener(eui.UIEvent.COMPLETE,function(){t()},e)})},t.prototype.createGameScene=function(){var e=(new egret.TextField,egret.getOption("shareuid")),t=egret.getOption("playuid"),n=egret.getOption("type"),i=egret.getOption("surplus"),r=index.NewIndex();r.play_uid=""==t?0:parseInt(t),r.share_uid=""==e?0:parseInt(e),r.share_type=""==n?1:parseInt(n),r.surplus=""==i?0:parseInt(i),r.init(),this.addChild(r)},t.prototype.createBitmapByName=function(e){var t=new egret.Bitmap,n=RES.getRes(e);return t.texture=n,t},t.prototype.startAnimation=function(e){var t=this,n=new egret.HtmlTextParser,i=e.map(function(e){return n.parse(e)}),r=this.textfield,o=-1,s=function(){o++,o>=i.length&&(o=0);var e=i[o];r.textFlow=e;var n=egret.Tween.get(r);n.to({alpha:1},200),n.wait(2e3),n.to({alpha:0},200),n.call(s,t)};s()},t.prototype.onButtonClick=function(e){var t=new eui.Panel;t.title="Title",t.horizontalCenter=0,t.verticalCenter=0,this.addChild(t)},t}(eui.UILayer);__reflect(Main.prototype,"Main");var DebugPlatform=function(){function e(){}return e.prototype.getUserInfo=function(){return __awaiter(this,void 0,void 0,function(){return __generator(this,function(e){return[2,{nickName:"username"}]})})},e.prototype.login=function(){return __awaiter(this,void 0,void 0,function(){return __generator(this,function(e){return[2]})})},e}();__reflect(DebugPlatform.prototype,"DebugPlatform",["Platform"]),window.platform||(window.platform=new DebugPlatform);var ThemeAdapter=function(){function e(){}return e.prototype.getTheme=function(e,t,n,i){function r(e){t.call(i,e)}function o(t){t.resItem.url==e&&(RES.removeEventListener(RES.ResourceEvent.ITEM_LOAD_ERROR,o,null),n.call(i))}"undefined"!=typeof generateEUI?egret.callLater(function(){t.call(i,generateEUI)},this):(RES.addEventListener(RES.ResourceEvent.ITEM_LOAD_ERROR,o,null),RES.getResByUrl(e,r,this,RES.ResourceItem.TYPE_TEXT))},e}();__reflect(ThemeAdapter.prototype,"ThemeAdapter",["eui.IThemeAdapter"]);var comm=function(){function e(){}return e.isNull=function(e){return null==e||void 0==e||""==e?!0:!1},e.playAnimation=function(e,t){if(t)for(var n in e.items)e.items[n].props={loop:!0};e.play()},e}();__reflect(comm.prototype,"comm");var conf=function(){function e(){}return e.LoginUrl="http://login.wangqianhong.com/",e.AgentUrl="http://test.agent.wangqianhong.com/",e}();__reflect(conf.prototype,"conf");var LoadingUI=function(e){function t(){var t=e.call(this)||this;return t.createView(),t}return __extends(t,e),t.prototype.createView=function(){this.textField=new egret.TextField,this.addChild(this.textField),this.textField.y=300,this.textField.width=480,this.textField.height=100,this.textField.textAlign="center"},t.prototype.onProgress=function(e,t){this.textField.text="Loading..."+e+"/"+t},t}(egret.Sprite);__reflect(LoadingUI.prototype,"LoadingUI",["RES.PromiseTaskReporter"]);var index=function(e){function t(){var t=e.call(this)||this;return t.play_uid=0,t.share_uid=0,t.share_type=1,t.surplus=0,t.skinName="index_skin",t}return __extends(t,e),t.NewIndex=function(){return new t},t.prototype.init=function(){comm.playAnimation(this.effect,!0),this.bindPlay(),1==this.share_type?(this.index_at_name.visible=!1,this.index_at_uid.visible=!1,this.index_at_agent.visible=!1,this.index_at_nick.visible=!1,this.index_nick.visible=!0,this.index_uid.visible=!0,this.index_at_agent.hasEventListener(egret.TouchEvent.TOUCH_TAP)&&(this.index_at_agent.touchEnabled=!1,this.index_at_agent.removeEventListener(egret.TouchEvent.TOUCH_TAP,this.onBecome,this))):(this.index_at_name.visible=!0,this.index_at_uid.visible=!0,this.index_at_agent.visible=!0,this.index_at_nick.visible=!0,this.index_nick.visible=!1,this.index_uid.visible=!1,this.index_at_agent.touchEnabled=!0,this.index_at_agent.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onBecome,this)),this.index_pointer.touchEnabled=!0,this.index_pointer.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onLottery,this),this.index_rule.touchEnabled=!0,this.index_rule.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onRule,this),this.index_myprize.touchEnabled=!0,this.index_myprize.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onMyPrize,this),this.index_download.touchEnabled=!0,this.index_download.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onDownload,this)},t.prototype.bindPlay=function(){if(0!=this.share_uid){var e=conf.AgentUrl+"/api/GetPlay/"+this.share_uid;HttpManager.create().Get(e,this.onSuccess,this.onLoadError,this)}},t.prototype.onLoadError=function(){console.log("GetPlay error")},t.prototype.onSuccess=function(e){var t=JSON.parse(e);comm.isNull(t)||(this.index_head.source=t.head,this.index_head.width=82,this.index_head.height=82,this.index_uid.text="ID:"+t.uid,this.index_nick.text=t.nick,this.index_at_nick.text=t.nick,this.index_at_uid.text=t.uid,this.index_surplus.text=this.surplus.toString())},t.prototype.onBecome=function(){window.location.href=conf.AgentUrl+"/PlayerBuy/index"+this.share_uid},t.prototype.onDownload=function(){window.location.href="http://fir.im/ysrn"},t.prototype.onMyPrize=function(){var e=myprize.NewMyPrize();e.play_uid=this.play_uid,e.init(),this.addChild(e)},t.prototype.onRule=function(){this.addChild(rule.NewRule())},t.prototype.onLottery=function(){var e="";if(this.play_uid<=0?e="您还没有游戏账号，请下载并登录游戏，再按抽奖规则，获取抽检机会！":this.surplus<=0&&(e="您的抽奖次数不足，请查看抽奖规则，获取抽奖机会!",this.index_surplus.text=this.surplus.toString()),""==e){var t=conf.AgentUrl+"/api/GetLottery/"+this.play_uid;HttpManager.create().Get(t,this.onLotterySuccess,this.onLotteryError,this)}else{var n=message.NewMessage();n.msg_text.text=e,this.addChild(n)}},t.prototype.onLotteryError=function(){console.log("lotter error")},t.prototype.onLotterySuccess=function(e){var t=this,n=JSON.parse(e);if(!comm.isNull(n)){var i="";if(1==n?i="您还没有游戏账号，请下载并登录游戏，再按抽奖规则，获取抽检机会！":2==n&&(i="您的抽奖次数不足，请查看抽奖规则，获取抽奖机会!",this.surplus=0,this.index_surplus.text=this.surplus.toString()),""==i){this.index_pointer.touchEnabled=!1;var r=n.id,o=n.value,s=1825.7-51.4*r,a=3*s;egret.Tween.get(this.index_prize).to({rotation:s},a,egret.Ease.sineOut).call(function(){t.index_pointer.touchEnabled=!0;var e=redbag.NewRedBag();e.start(o),t.addChild(e)}),this.surplus-=1,this.index_surplus.text=this.surplus.toString()}else{var h=message.NewMessage();h.msg_text.text=i,this.addChild(h)}}},t}(eui.Component);__reflect(index.prototype,"index");var message=function(e){function t(){var t=e.call(this)||this;return t.skinName="message_skin",t.init(),t}return __extends(t,e),t.NewMessage=function(){return new t},t.prototype.init=function(){this.msg_close.touchEnabled=!0,this.msg_close.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onClose,this)},t.prototype.onClose=function(){this.msg_close.hasEventListener(egret.TouchEvent.TOUCH_TAP)&&(this.msg_close.touchEnabled=!1,this.msg_close.removeEventListener(egret.TouchEvent.TOUCH_TAP,this.onClose,this)),this.parent&&this.parent.removeChild(this)},t}(eui.Component);__reflect(message.prototype,"message");var myprize=function(e){function t(){var t=e.call(this)||this;return t.play_uid=0,t.amount=0,t.skinName="myprize_skin",t}return __extends(t,e),t.NewMyPrize=function(){return new t},t.prototype.init=function(){this.bindPlay(),this.mp_obtain.touchEnabled=!0,this.mp_obtain.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onObtain,this),this.mp_notes.touchEnabled=!0,this.mp_notes.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onNotes,this),this.mp_receive.touchEnabled=!0,this.mp_receive.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onReceive,this),this.mp_close.touchEnabled=!0,this.mp_close.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onClose,this)},t.prototype.onNotes=function(){var e=record.NewRecord();e.init(this.play_uid),this.addChild(e)},t.prototype.onReceive=function(){var e=obainlist.NewObainList();e.init(this.play_uid),this.addChild(e)},t.prototype.bindPlay=function(){if(0!=this.play_uid){var e=conf.AgentUrl+"/api/GetPlay/"+this.play_uid;HttpManager.create().Get(e,this.onPlaySuccess,this.onLoadPlayError,this)}},t.prototype.onLoadPlayError=function(){console.log("myprize GetPlay error")},t.prototype.onPlaySuccess=function(e){var t=JSON.parse(e);comm.isNull(t)||(this.mp_head.source=t.head,this.mp_head.width=82,this.mp_head.height=82,this.mp_uid.text="ID:"+t.uid,this.mp_nick.text=t.nick,this.mp_amount.text=t.amount,this.amount=t.amount)},t.prototype.onClose=function(){this.mp_close.hasEventListener(egret.TouchEvent.TOUCH_TAP)&&(this.mp_close.touchEnabled=!1,this.mp_close.removeEventListener(egret.TouchEvent.TOUCH_TAP,this.onClose,this)),this.mp_obtain.hasEventListener(egret.TouchEvent.TOUCH_TAP)&&(this.mp_obtain.touchEnabled=!1,this.mp_obtain.removeEventListener(egret.TouchEvent.TOUCH_TAP,this.onObtain,this)),this.mp_notes.hasEventListener(egret.TouchEvent.TOUCH_TAP)&&(this.mp_notes.touchEnabled=!1,this.mp_notes.removeEventListener(egret.TouchEvent.TOUCH_TAP,this.onNotes,this)),this.mp_receive.hasEventListener(egret.TouchEvent.TOUCH_TAP)&&(this.mp_receive.touchEnabled=!1,this.mp_receive.removeEventListener(egret.TouchEvent.TOUCH_TAP,this.onReceive,this)),this.parent&&this.parent.removeChild(this)},t.prototype.onObtain=function(){var e="";if(this.play_uid<=0?e="您还没有游戏账号，请下载并登录游戏！":this.amount<1&&(e="您的红包金额不足1元，不能领取!"),""==e){var t=conf.AgentUrl+"/redpack/"+this.play_uid;HttpManager.create().Get(t,this.onExtractSuccess,this.onExtractError,this)}else{var n=message.NewMessage();n.msg_text.text=e,this.addChild(n)}},t.prototype.onExtractError=function(){console.log("Extract error")},t.prototype.onExtractSuccess=function(e){if(!comm.isNull(e)){var t="";"1"==e?(t="领取成功，请到公众号领取红包！",this.amount=0,this.mp_amount.text=this.amount.toString()):t="UID_ERROR"==e?"玩家UID错误！":"AMOUNT_ERROR"==e?"您的红包金额不足1元，不能领取":"NO_AUTH"==e?"您的微信账号异常，已被微信拦截！":"SENDNUM_LIMIT"==e?"您今日领取次数超过限制！":"OPENID_ERROR"==e?"请未关注公众号，不能领取！":"系统忙，请稍后再试！";var n=message.NewMessage();n.msg_text.text=t,this.addChild(n)}},t}(eui.Component);__reflect(myprize.prototype,"myprize");var obainlist=function(e){function t(){var t=e.call(this)||this;return t.skinName="obainlist_skin",t}return __extends(t,e),t.NewObainList=function(){return new t},t.prototype.init=function(e){if(0!=e){var t=conf.AgentUrl+"/api/GetObainList/"+e;HttpManager.create().Get(t,this.onSuccess,this.onLoadError,this)}this.ob_close.touchEnabled=!0,this.ob_close.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onClose,this)},t.prototype.onLoadError=function(){console.log("error")},t.prototype.onSuccess=function(e){var t=JSON.parse(e);if(!comm.isNull(t)){for(var n=[],i=0,r=t;i<r.length;i++){var o=r[i];n.push({name:o.gold+"元",date:o.create_time})}this.ob_data.dataProvider=new eui.ArrayCollection(n)}},t.prototype.onClose=function(){this.ob_close.hasEventListener(egret.TouchEvent.TOUCH_TAP)&&(this.ob_close.touchEnabled=!1,this.ob_close.removeEventListener(egret.TouchEvent.TOUCH_TAP,this.onClose,this)),this.parent&&this.parent.removeChild(this)},t}(eui.Component);__reflect(obainlist.prototype,"obainlist");var record=function(e){function t(){var t=e.call(this)||this;return t.skinName="record_skin",t}return __extends(t,e),t.NewRecord=function(){return new t},t.prototype.init=function(e){if(0!=e){var t=conf.AgentUrl+"/api/GetRadList/"+e;HttpManager.create().Get(t,this.onSuccess,this.onLoadError,this)}this.rec_close.touchEnabled=!0,this.rec_close.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onClose,this)},t.prototype.onLoadError=function(){console.log("error")},t.prototype.onSuccess=function(e){var t=JSON.parse(e);if(!comm.isNull(t)){for(var n=[],i=0,r=t;i<r.length;i++){var o=r[i];n.push({name:o.name,date:o.u_date})}this.rec_data.dataProvider=new eui.ArrayCollection(n)}},t.prototype.onClose=function(){this.rec_close.hasEventListener(egret.TouchEvent.TOUCH_TAP)&&(this.rec_close.touchEnabled=!1,this.rec_close.removeEventListener(egret.TouchEvent.TOUCH_TAP,this.onClose,this)),this.parent&&this.parent.removeChild(this)},t}(eui.Component);__reflect(record.prototype,"record");var redbag=function(e){function t(){var t=e.call(this)||this;return t.skinName="redbag_skin",t}return __extends(t,e),t.NewRedBag=function(){return new t},t.prototype.start=function(e){this.rb_title.text="获得"+e+"元红包",this.rb_money.text=e+"元",egret.Tween.get(this.rb_light,{loop:!0}).to({rotation:360},1e4),this.showred.play(),this.rb_query.touchEnabled=!0,this.rb_query.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onQuery,this)},t.prototype.onQuery=function(){this.rb_title.text="",this.rb_money.text="",egret.Tween.removeAllTweens(),this.rb_query.hasEventListener(egret.TouchEvent.TOUCH_TAP)&&(this.rb_query.touchEnabled=!1,this.rb_query.removeEventListener(egret.TouchEvent.TOUCH_TAP,this.onQuery,this)),this.parent&&this.parent.removeChild(this)},t}(eui.Component);__reflect(redbag.prototype,"redbag");var rule=function(e){function t(){var t=e.call(this)||this;return t.skinName="rule_skin",t.init(),t}return __extends(t,e),t.NewRule=function(){return new t},t.prototype.init=function(){this.rule_close.touchEnabled=!0,this.rule_close.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onClose,this)},t.prototype.onClose=function(){this.rule_close.hasEventListener(egret.TouchEvent.TOUCH_TAP)&&(this.rule_close.touchEnabled=!1,this.rule_close.removeEventListener(egret.TouchEvent.TOUCH_TAP,this.onClose,this)),this.parent&&this.parent.removeChild(this)},t}(eui.Component);__reflect(rule.prototype,"rule");