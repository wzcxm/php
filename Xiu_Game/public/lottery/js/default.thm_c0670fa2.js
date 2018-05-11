window.skins={};
function __extends(d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
        function __() {
            this.constructor = d;
        }
    __.prototype = b.prototype;
    d.prototype = new __();
};
window.generateEUI = {};
generateEUI.paths = {};
generateEUI.styles = undefined;
generateEUI.skins = {"eui.Button":"resource/eui_skins/ButtonSkin.exml","eui.CheckBox":"resource/eui_skins/CheckBoxSkin.exml","eui.HScrollBar":"resource/eui_skins/HScrollBarSkin.exml","eui.HSlider":"resource/eui_skins/HSliderSkin.exml","eui.Panel":"resource/eui_skins/PanelSkin.exml","eui.TextInput":"resource/eui_skins/TextInputSkin.exml","eui.ProgressBar":"resource/eui_skins/ProgressBarSkin.exml","eui.RadioButton":"resource/eui_skins/RadioButtonSkin.exml","eui.Scroller":"resource/eui_skins/ScrollerSkin.exml","eui.ToggleSwitch":"resource/eui_skins/ToggleSwitchSkin.exml","eui.VScrollBar":"resource/eui_skins/VScrollBarSkin.exml","eui.VSlider":"resource/eui_skins/VSliderSkin.exml","eui.ItemRenderer":"resource/eui_skins/ItemRendererSkin.exml"}
generateEUI.paths['resource/eui_skins/ButtonSkin.exml'] = window.skins.ButtonSkin = (function (_super) {
	__extends(ButtonSkin, _super);
	function ButtonSkin() {
		_super.call(this);
		this.skinParts = ["labelDisplay","iconDisplay"];
		
		this.minHeight = 50;
		this.minWidth = 100;
		this.elementsContent = [this._Image1_i(),this.labelDisplay_i(),this.iconDisplay_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Image1","source","button_down_png")
				])
			,
			new eui.State ("disabled",
				[
					new eui.SetProperty("_Image1","alpha",0.5)
				])
		];
	}
	var _proto = ButtonSkin.prototype;

	_proto._Image1_i = function () {
		var t = new eui.Image();
		this._Image1 = t;
		t.percentHeight = 100;
		t.scale9Grid = new egret.Rectangle(1,3,8,8);
		t.source = "button_up_png";
		t.percentWidth = 100;
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.bottom = 8;
		t.left = 8;
		t.right = 8;
		t.size = 20;
		t.textAlign = "center";
		t.textColor = 0xFFFFFF;
		t.top = 8;
		t.verticalAlign = "middle";
		return t;
	};
	_proto.iconDisplay_i = function () {
		var t = new eui.Image();
		this.iconDisplay = t;
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		return t;
	};
	return ButtonSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/CheckBoxSkin.exml'] = window.skins.CheckBoxSkin = (function (_super) {
	__extends(CheckBoxSkin, _super);
	function CheckBoxSkin() {
		_super.call(this);
		this.skinParts = ["labelDisplay"];
		
		this.elementsContent = [this._Group1_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Image1","alpha",0.7)
				])
			,
			new eui.State ("disabled",
				[
					new eui.SetProperty("_Image1","alpha",0.5)
				])
			,
			new eui.State ("upAndSelected",
				[
					new eui.SetProperty("_Image1","source","checkbox_select_up_png")
				])
			,
			new eui.State ("downAndSelected",
				[
					new eui.SetProperty("_Image1","source","checkbox_select_down_png")
				])
			,
			new eui.State ("disabledAndSelected",
				[
					new eui.SetProperty("_Image1","source","checkbox_select_disabled_png")
				])
		];
	}
	var _proto = CheckBoxSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		this._Image1 = t;
		t.alpha = 1;
		t.fillMode = "scale";
		t.source = "checkbox_unselect_png";
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.fontFamily = "Tahoma";
		t.size = 20;
		t.textAlign = "center";
		t.textColor = 0x707070;
		t.verticalAlign = "middle";
		return t;
	};
	return CheckBoxSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/HScrollBarSkin.exml'] = window.skins.HScrollBarSkin = (function (_super) {
	__extends(HScrollBarSkin, _super);
	function HScrollBarSkin() {
		_super.call(this);
		this.skinParts = ["thumb"];
		
		this.minHeight = 8;
		this.minWidth = 20;
		this.elementsContent = [this.thumb_i()];
	}
	var _proto = HScrollBarSkin.prototype;

	_proto.thumb_i = function () {
		var t = new eui.Image();
		this.thumb = t;
		t.height = 8;
		t.scale9Grid = new egret.Rectangle(3,3,2,2);
		t.source = "roundthumb_png";
		t.verticalCenter = 0;
		t.width = 30;
		return t;
	};
	return HScrollBarSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/HSliderSkin.exml'] = window.skins.HSliderSkin = (function (_super) {
	__extends(HSliderSkin, _super);
	function HSliderSkin() {
		_super.call(this);
		this.skinParts = ["track","thumb"];
		
		this.minHeight = 8;
		this.minWidth = 20;
		this.elementsContent = [this.track_i(),this.thumb_i()];
	}
	var _proto = HSliderSkin.prototype;

	_proto.track_i = function () {
		var t = new eui.Image();
		this.track = t;
		t.height = 6;
		t.scale9Grid = new egret.Rectangle(1,1,4,4);
		t.source = "track_sb_png";
		t.verticalCenter = 0;
		t.percentWidth = 100;
		return t;
	};
	_proto.thumb_i = function () {
		var t = new eui.Image();
		this.thumb = t;
		t.source = "thumb_png";
		t.verticalCenter = 0;
		return t;
	};
	return HSliderSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/ItemRendererSkin.exml'] = window.skins.ItemRendererSkin = (function (_super) {
	__extends(ItemRendererSkin, _super);
	function ItemRendererSkin() {
		_super.call(this);
		this.skinParts = ["labelDisplay"];
		
		this.minHeight = 50;
		this.minWidth = 100;
		this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Image1","source","button_down_png")
				])
			,
			new eui.State ("disabled",
				[
					new eui.SetProperty("_Image1","alpha",0.5)
				])
		];
		
		eui.Binding.$bindProperties(this, ["hostComponent.data"],[0],this.labelDisplay,"text")
	}
	var _proto = ItemRendererSkin.prototype;

	_proto._Image1_i = function () {
		var t = new eui.Image();
		this._Image1 = t;
		t.percentHeight = 100;
		t.scale9Grid = new egret.Rectangle(1,3,8,8);
		t.source = "button_up_png";
		t.percentWidth = 100;
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.bottom = 8;
		t.fontFamily = "Tahoma";
		t.left = 8;
		t.right = 8;
		t.size = 20;
		t.textAlign = "center";
		t.textColor = 0xFFFFFF;
		t.top = 8;
		t.verticalAlign = "middle";
		return t;
	};
	return ItemRendererSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/PanelSkin.exml'] = window.skins.PanelSkin = (function (_super) {
	__extends(PanelSkin, _super);
	function PanelSkin() {
		_super.call(this);
		this.skinParts = ["titleDisplay","moveArea","closeButton"];
		
		this.minHeight = 230;
		this.minWidth = 450;
		this.elementsContent = [this._Image1_i(),this.moveArea_i(),this.closeButton_i()];
	}
	var _proto = PanelSkin.prototype;

	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.scale9Grid = new egret.Rectangle(2,2,12,12);
		t.source = "border_png";
		t.top = 0;
		return t;
	};
	_proto.moveArea_i = function () {
		var t = new eui.Group();
		this.moveArea = t;
		t.height = 45;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.elementsContent = [this._Image2_i(),this.titleDisplay_i()];
		return t;
	};
	_proto._Image2_i = function () {
		var t = new eui.Image();
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.source = "header_png";
		t.top = 0;
		return t;
	};
	_proto.titleDisplay_i = function () {
		var t = new eui.Label();
		this.titleDisplay = t;
		t.fontFamily = "Tahoma";
		t.left = 15;
		t.right = 5;
		t.size = 20;
		t.textColor = 0xFFFFFF;
		t.verticalCenter = 0;
		t.wordWrap = false;
		return t;
	};
	_proto.closeButton_i = function () {
		var t = new eui.Button();
		this.closeButton = t;
		t.bottom = 5;
		t.horizontalCenter = 0;
		t.label = "close";
		return t;
	};
	return PanelSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/ProgressBarSkin.exml'] = window.skins.ProgressBarSkin = (function (_super) {
	__extends(ProgressBarSkin, _super);
	function ProgressBarSkin() {
		_super.call(this);
		this.skinParts = ["thumb","labelDisplay"];
		
		this.minHeight = 18;
		this.minWidth = 30;
		this.elementsContent = [this._Image1_i(),this.thumb_i(),this.labelDisplay_i()];
	}
	var _proto = ProgressBarSkin.prototype;

	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.percentHeight = 100;
		t.scale9Grid = new egret.Rectangle(1,1,4,4);
		t.source = "track_pb_png";
		t.verticalCenter = 0;
		t.percentWidth = 100;
		return t;
	};
	_proto.thumb_i = function () {
		var t = new eui.Image();
		this.thumb = t;
		t.percentHeight = 100;
		t.source = "thumb_pb_png";
		t.percentWidth = 100;
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.fontFamily = "Tahoma";
		t.horizontalCenter = 0;
		t.size = 15;
		t.textAlign = "center";
		t.textColor = 0x707070;
		t.verticalAlign = "middle";
		t.verticalCenter = 0;
		return t;
	};
	return ProgressBarSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/RadioButtonSkin.exml'] = window.skins.RadioButtonSkin = (function (_super) {
	__extends(RadioButtonSkin, _super);
	function RadioButtonSkin() {
		_super.call(this);
		this.skinParts = ["labelDisplay"];
		
		this.elementsContent = [this._Group1_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Image1","alpha",0.7)
				])
			,
			new eui.State ("disabled",
				[
					new eui.SetProperty("_Image1","alpha",0.5)
				])
			,
			new eui.State ("upAndSelected",
				[
					new eui.SetProperty("_Image1","source","radiobutton_select_up_png")
				])
			,
			new eui.State ("downAndSelected",
				[
					new eui.SetProperty("_Image1","source","radiobutton_select_down_png")
				])
			,
			new eui.State ("disabledAndSelected",
				[
					new eui.SetProperty("_Image1","source","radiobutton_select_disabled_png")
				])
		];
	}
	var _proto = RadioButtonSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		this._Image1 = t;
		t.alpha = 1;
		t.fillMode = "scale";
		t.source = "radiobutton_unselect_png";
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.fontFamily = "Tahoma";
		t.size = 20;
		t.textAlign = "center";
		t.textColor = 0x707070;
		t.verticalAlign = "middle";
		return t;
	};
	return RadioButtonSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/ScrollerSkin.exml'] = window.skins.ScrollerSkin = (function (_super) {
	__extends(ScrollerSkin, _super);
	function ScrollerSkin() {
		_super.call(this);
		this.skinParts = ["horizontalScrollBar","verticalScrollBar"];
		
		this.minHeight = 20;
		this.minWidth = 20;
		this.elementsContent = [this.horizontalScrollBar_i(),this.verticalScrollBar_i()];
	}
	var _proto = ScrollerSkin.prototype;

	_proto.horizontalScrollBar_i = function () {
		var t = new eui.HScrollBar();
		this.horizontalScrollBar = t;
		t.bottom = 0;
		t.percentWidth = 100;
		return t;
	};
	_proto.verticalScrollBar_i = function () {
		var t = new eui.VScrollBar();
		this.verticalScrollBar = t;
		t.percentHeight = 100;
		t.right = 0;
		return t;
	};
	return ScrollerSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/TextInputSkin.exml'] = window.skins.TextInputSkin = (function (_super) {
	__extends(TextInputSkin, _super);
	function TextInputSkin() {
		_super.call(this);
		this.skinParts = ["textDisplay","promptDisplay"];
		
		this.minHeight = 40;
		this.minWidth = 300;
		this.elementsContent = [this._Image1_i(),this._Rect1_i(),this.textDisplay_i()];
		this.promptDisplay_i();
		
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("disabled",
				[
					new eui.SetProperty("textDisplay","textColor",0xff0000)
				])
			,
			new eui.State ("normalWithPrompt",
				[
					new eui.AddItems("promptDisplay","",1,"")
				])
			,
			new eui.State ("disabledWithPrompt",
				[
					new eui.AddItems("promptDisplay","",1,"")
				])
		];
	}
	var _proto = TextInputSkin.prototype;

	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.percentHeight = 100;
		t.scale9Grid = new egret.Rectangle(1,3,8,8);
		t.source = "button_up_png";
		t.percentWidth = 100;
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0xffffff;
		t.percentHeight = 100;
		t.percentWidth = 100;
		return t;
	};
	_proto.textDisplay_i = function () {
		var t = new eui.EditableText();
		this.textDisplay = t;
		t.height = 24;
		t.left = "10";
		t.right = "10";
		t.size = 20;
		t.textColor = 0x000000;
		t.verticalCenter = "0";
		t.percentWidth = 100;
		return t;
	};
	_proto.promptDisplay_i = function () {
		var t = new eui.Label();
		this.promptDisplay = t;
		t.height = 24;
		t.left = 10;
		t.right = 10;
		t.size = 20;
		t.textColor = 0xa9a9a9;
		t.touchEnabled = false;
		t.verticalCenter = 0;
		t.percentWidth = 100;
		return t;
	};
	return TextInputSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/ToggleSwitchSkin.exml'] = window.skins.ToggleSwitchSkin = (function (_super) {
	__extends(ToggleSwitchSkin, _super);
	function ToggleSwitchSkin() {
		_super.call(this);
		this.skinParts = [];
		
		this.elementsContent = [this._Image1_i(),this._Image2_i()];
		this.states = [
			new eui.State ("up",
				[
					new eui.SetProperty("_Image1","source","off_png")
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Image1","source","off_png")
				])
			,
			new eui.State ("disabled",
				[
					new eui.SetProperty("_Image1","source","off_png")
				])
			,
			new eui.State ("upAndSelected",
				[
					new eui.SetProperty("_Image2","horizontalCenter",18)
				])
			,
			new eui.State ("downAndSelected",
				[
					new eui.SetProperty("_Image2","horizontalCenter",18)
				])
			,
			new eui.State ("disabledAndSelected",
				[
					new eui.SetProperty("_Image2","horizontalCenter",18)
				])
		];
	}
	var _proto = ToggleSwitchSkin.prototype;

	_proto._Image1_i = function () {
		var t = new eui.Image();
		this._Image1 = t;
		t.source = "on_png";
		return t;
	};
	_proto._Image2_i = function () {
		var t = new eui.Image();
		this._Image2 = t;
		t.horizontalCenter = -18;
		t.source = "handle_png";
		t.verticalCenter = 0;
		return t;
	};
	return ToggleSwitchSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/VScrollBarSkin.exml'] = window.skins.VScrollBarSkin = (function (_super) {
	__extends(VScrollBarSkin, _super);
	function VScrollBarSkin() {
		_super.call(this);
		this.skinParts = ["thumb"];
		
		this.minHeight = 20;
		this.minWidth = 8;
		this.elementsContent = [this.thumb_i()];
	}
	var _proto = VScrollBarSkin.prototype;

	_proto.thumb_i = function () {
		var t = new eui.Image();
		this.thumb = t;
		t.height = 30;
		t.horizontalCenter = 0;
		t.scale9Grid = new egret.Rectangle(3,3,2,2);
		t.source = "roundthumb_png";
		t.width = 8;
		return t;
	};
	return VScrollBarSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/VSliderSkin.exml'] = window.skins.VSliderSkin = (function (_super) {
	__extends(VSliderSkin, _super);
	function VSliderSkin() {
		_super.call(this);
		this.skinParts = ["track","thumb"];
		
		this.minHeight = 30;
		this.minWidth = 25;
		this.elementsContent = [this.track_i(),this.thumb_i()];
	}
	var _proto = VSliderSkin.prototype;

	_proto.track_i = function () {
		var t = new eui.Image();
		this.track = t;
		t.percentHeight = 100;
		t.horizontalCenter = 0;
		t.scale9Grid = new egret.Rectangle(1,1,4,4);
		t.source = "track_png";
		t.width = 7;
		return t;
	};
	_proto.thumb_i = function () {
		var t = new eui.Image();
		this.thumb = t;
		t.horizontalCenter = 0;
		t.source = "thumb_png";
		return t;
	};
	return VSliderSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/index/index.exml'] = window.index_skin = (function (_super) {
	__extends(index_skin, _super);
	var index_skin$Skin1 = 	(function (_super) {
		__extends(index_skin$Skin1, _super);
		function index_skin$Skin1() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = index_skin$Skin1.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "agent_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return index_skin$Skin1;
	})(eui.Skin);

	var index_skin$Skin2 = 	(function (_super) {
		__extends(index_skin$Skin2, _super);
		function index_skin$Skin2() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = index_skin$Skin2.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "rule_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return index_skin$Skin2;
	})(eui.Skin);

	var index_skin$Skin3 = 	(function (_super) {
		__extends(index_skin$Skin3, _super);
		function index_skin$Skin3() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = index_skin$Skin3.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "download_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return index_skin$Skin3;
	})(eui.Skin);

	var index_skin$Skin4 = 	(function (_super) {
		__extends(index_skin$Skin4, _super);
		function index_skin$Skin4() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = index_skin$Skin4.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "myprize_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return index_skin$Skin4;
	})(eui.Skin);

	function index_skin() {
		_super.call(this);
		this.skinParts = ["effect","index_head","index_nick","index_at_name","index_uid","index_at_uid","index_at_nick","index_at_agent","index_ring_one","index_ring_two","index_prize","index_pointer","index_surplus","index_rule","index_download","index_myprize"];
		
		this.height = 1206;
		this.width = 750;
		this.effect_i();
		this.elementsContent = [this._Image1_i(),this.index_head_i(),this._Image2_i(),this.index_nick_i(),this.index_at_name_i(),this.index_uid_i(),this.index_at_uid_i(),this.index_at_nick_i(),this.index_at_agent_i(),this.index_ring_one_i(),this.index_ring_two_i(),this.index_prize_i(),this.index_pointer_i(),this.index_surplus_i(),this.index_rule_i(),this.index_download_i(),this.index_myprize_i()];
		
		eui.Binding.$bindProperties(this, ["index_ring_one"],[0],this._TweenItem1,"target")
		eui.Binding.$bindProperties(this, [1],[],this._Object1,"alpha")
		eui.Binding.$bindProperties(this, [0],[],this._Object2,"alpha")
		eui.Binding.$bindProperties(this, ["index_ring_two"],[0],this._TweenItem2,"target")
		eui.Binding.$bindProperties(this, [0],[],this._Object3,"alpha")
		eui.Binding.$bindProperties(this, [1],[],this._Object4,"alpha")
		eui.Binding.$bindProperties(this, [0],[],this._Object5,"alpha")
	}
	var _proto = index_skin.prototype;

	_proto.effect_i = function () {
		var t = new egret.tween.TweenGroup();
		this.effect = t;
		t.items = [this._TweenItem1_i(),this._TweenItem2_i()];
		return t;
	};
	_proto._TweenItem1_i = function () {
		var t = new egret.tween.TweenItem();
		this._TweenItem1 = t;
		t.paths = [this._Set1_i(),this._To1_i()];
		return t;
	};
	_proto._Set1_i = function () {
		var t = new egret.tween.Set();
		t.props = this._Object1_i();
		return t;
	};
	_proto._Object1_i = function () {
		var t = {};
		this._Object1 = t;
		return t;
	};
	_proto._To1_i = function () {
		var t = new egret.tween.To();
		t.duration = 500;
		t.props = this._Object2_i();
		return t;
	};
	_proto._Object2_i = function () {
		var t = {};
		this._Object2 = t;
		return t;
	};
	_proto._TweenItem2_i = function () {
		var t = new egret.tween.TweenItem();
		this._TweenItem2 = t;
		t.paths = [this._Set2_i(),this._Wait1_i(),this._Set3_i(),this._To2_i()];
		return t;
	};
	_proto._Set2_i = function () {
		var t = new egret.tween.Set();
		t.props = this._Object3_i();
		return t;
	};
	_proto._Object3_i = function () {
		var t = {};
		this._Object3 = t;
		return t;
	};
	_proto._Wait1_i = function () {
		var t = new egret.tween.Wait();
		t.duration = 500;
		return t;
	};
	_proto._Set3_i = function () {
		var t = new egret.tween.Set();
		t.props = this._Object4_i();
		return t;
	};
	_proto._Object4_i = function () {
		var t = {};
		this._Object4 = t;
		return t;
	};
	_proto._To2_i = function () {
		var t = new egret.tween.To();
		t.duration = 500;
		t.props = this._Object5_i();
		return t;
	};
	_proto._Object5_i = function () {
		var t = {};
		this._Object5 = t;
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.horizontalCenter = 0;
		t.source = "bg_png";
		t.verticalCenter = 0;
		return t;
	};
	_proto.index_head_i = function () {
		var t = new eui.Image();
		this.index_head = t;
		t.left = 25;
		t.source = "def_head_png";
		t.top = 30;
		return t;
	};
	_proto._Image2_i = function () {
		var t = new eui.Image();
		t.left = 18;
		t.source = "head_png";
		t.top = 23;
		return t;
	};
	_proto.index_nick_i = function () {
		var t = new eui.Label();
		this.index_nick = t;
		t.fontFamily = "SimHei";
		t.left = 126;
		t.size = 35;
		t.text = "";
		t.top = 35;
		return t;
	};
	_proto.index_at_name_i = function () {
		var t = new eui.Label();
		this.index_at_name = t;
		t.fontFamily = "SimHei";
		t.left = 126;
		t.size = 35;
		t.text = "代理ID：";
		t.textColor = 0xfeeb09;
		t.top = 35;
		t.visible = false;
		return t;
	};
	_proto.index_uid_i = function () {
		var t = new eui.Label();
		this.index_uid = t;
		t.fontFamily = "SimHei";
		t.left = 126;
		t.size = 35;
		t.text = "ID:";
		t.top = 73;
		return t;
	};
	_proto.index_at_uid_i = function () {
		var t = new eui.Label();
		this.index_at_uid = t;
		t.fontFamily = "SimHei";
		t.left = 126;
		t.size = 35;
		t.text = "";
		t.top = 76;
		t.visible = false;
		return t;
	};
	_proto.index_at_nick_i = function () {
		var t = new eui.Label();
		this.index_at_nick = t;
		t.fontFamily = "SimHei";
		t.left = 18;
		t.text = "";
		t.textColor = 0xacaafc;
		t.top = 120;
		t.visible = false;
		return t;
	};
	_proto.index_at_agent_i = function () {
		var t = new eui.Button();
		this.index_at_agent = t;
		t.label = "";
		t.left = 323;
		t.top = 23;
		t.visible = false;
		t.skinName = index_skin$Skin1;
		return t;
	};
	_proto.index_ring_one_i = function () {
		var t = new eui.Image();
		this.index_ring_one = t;
		t.horizontalCenter = 0.5;
		t.source = "ring_one_png";
		t.y = 308;
		return t;
	};
	_proto.index_ring_two_i = function () {
		var t = new eui.Image();
		this.index_ring_two = t;
		t.horizontalCenter = 0.5;
		t.source = "ring_two_png";
		t.y = 308;
		return t;
	};
	_proto.index_prize_i = function () {
		var t = new eui.Image();
		this.index_prize = t;
		t.horizontalCenter = 1;
		t.source = "prize_png";
		t.verticalCenter = 20;
		return t;
	};
	_proto.index_pointer_i = function () {
		var t = new eui.Image();
		this.index_pointer = t;
		t.horizontalCenter = 1;
		t.source = "pointer_png";
		t.top = 489;
		return t;
	};
	_proto.index_surplus_i = function () {
		var t = new eui.Label();
		this.index_surplus = t;
		t.size = 26;
		t.text = "0";
		t.textColor = 0xfde72c;
		t.x = 673;
		t.y = 333.79;
		return t;
	};
	_proto.index_rule_i = function () {
		var t = new eui.Button();
		this.index_rule = t;
		t.bottom = 99;
		t.label = "";
		t.left = 31;
		t.skinName = index_skin$Skin2;
		return t;
	};
	_proto.index_download_i = function () {
		var t = new eui.Button();
		this.index_download = t;
		t.bottom = 99;
		t.horizontalCenter = 0;
		t.label = "";
		t.skinName = index_skin$Skin3;
		return t;
	};
	_proto.index_myprize_i = function () {
		var t = new eui.Button();
		this.index_myprize = t;
		t.bottom = 99;
		t.label = "";
		t.right = 30;
		t.skinName = index_skin$Skin4;
		return t;
	};
	return index_skin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/window/datarow.exml'] = window.datarow = (function (_super) {
	__extends(datarow, _super);
	function datarow() {
		_super.call(this);
		this.skinParts = [];
		
		this.height = 60;
		this.width = 510;
		this.elementsContent = [this._Label1_i(),this._Label2_i()];
		
		eui.Binding.$bindProperties(this, ["hostComponent.data.name"],[0],this._Label1,"text")
		eui.Binding.$bindProperties(this, ["hostComponent.data.date"],[0],this._Label2,"text")
	}
	var _proto = datarow.prototype;

	_proto._Label1_i = function () {
		var t = new eui.Label();
		this._Label1 = t;
		t.left = 21;
		t.textColor = 0x6c6c6c;
		t.verticalCenter = 0;
		return t;
	};
	_proto._Label2_i = function () {
		var t = new eui.Label();
		this._Label2 = t;
		t.right = 14;
		t.textColor = 0x6c6c6c;
		t.verticalCenter = 0;
		return t;
	};
	return datarow;
})(eui.Skin);generateEUI.paths['resource/eui_skins/window/message.exml'] = window.message_skin = (function (_super) {
	__extends(message_skin, _super);
	var message_skin$Skin5 = 	(function (_super) {
		__extends(message_skin$Skin5, _super);
		function message_skin$Skin5() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = message_skin$Skin5.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "close_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return message_skin$Skin5;
	})(eui.Skin);

	function message_skin() {
		_super.call(this);
		this.skinParts = ["msg_text","msg_close"];
		
		this.height = 1206;
		this.width = 750;
		this.elementsContent = [this._Rect1_i(),this._Image1_i(),this.msg_text_i(),this.msg_close_i()];
	}
	var _proto = message_skin.prototype;

	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.fillAlpha = 0.5;
		t.height = 1206;
		t.width = 750;
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.horizontalCenter = 0.5;
		t.scale9Grid = new egret.Rectangle(10,10,10,10);
		t.source = "message_png";
		t.top = 217;
		return t;
	};
	_proto.msg_text_i = function () {
		var t = new eui.Label();
		this.msg_text = t;
		t.anchorOffsetX = 0;
		t.fontFamily = "KaiTi_GB2312";
		t.horizontalCenter = 0.5;
		t.size = 28;
		t.text = "asdasd";
		t.textAlign = "center";
		t.textColor = 0x000000;
		t.width = 465;
		t.y = 447.5;
		return t;
	};
	_proto.msg_close_i = function () {
		var t = new eui.Button();
		this.msg_close = t;
		t.bottom = 420;
		t.horizontalCenter = 0.5;
		t.label = "";
		t.skinName = message_skin$Skin5;
		return t;
	};
	return message_skin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/window/myprize.exml'] = window.myprize_skin = (function (_super) {
	__extends(myprize_skin, _super);
	var myprize_skin$Skin6 = 	(function (_super) {
		__extends(myprize_skin$Skin6, _super);
		function myprize_skin$Skin6() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = myprize_skin$Skin6.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "close_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return myprize_skin$Skin6;
	})(eui.Skin);

	var myprize_skin$Skin7 = 	(function (_super) {
		__extends(myprize_skin$Skin7, _super);
		function myprize_skin$Skin7() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = myprize_skin$Skin7.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "obtain_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return myprize_skin$Skin7;
	})(eui.Skin);

	var myprize_skin$Skin8 = 	(function (_super) {
		__extends(myprize_skin$Skin8, _super);
		function myprize_skin$Skin8() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = myprize_skin$Skin8.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "notes_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return myprize_skin$Skin8;
	})(eui.Skin);

	var myprize_skin$Skin9 = 	(function (_super) {
		__extends(myprize_skin$Skin9, _super);
		function myprize_skin$Skin9() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = myprize_skin$Skin9.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "receive_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return myprize_skin$Skin9;
	})(eui.Skin);

	function myprize_skin() {
		_super.call(this);
		this.skinParts = ["mp_close","mp_head","mp_nick","mp_uid","mp_amount","mp_obtain","mp_notes","mp_receive"];
		
		this.height = 1206;
		this.width = 750;
		this.elementsContent = [this._Rect1_i(),this._Image1_i(),this.mp_close_i(),this.mp_head_i(),this._Image2_i(),this.mp_nick_i(),this.mp_uid_i(),this.mp_amount_i(),this.mp_obtain_i(),this.mp_notes_i(),this.mp_receive_i()];
	}
	var _proto = myprize_skin.prototype;

	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.fillAlpha = 0.5;
		t.height = 1206;
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		t.width = 750;
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.horizontalCenter = 0;
		t.source = "myred_png";
		t.verticalCenter = -81.5;
		return t;
	};
	_proto.mp_close_i = function () {
		var t = new eui.Button();
		this.mp_close = t;
		t.bottom = 126;
		t.horizontalCenter = 0;
		t.label = "";
		t.skinName = myprize_skin$Skin6;
		return t;
	};
	_proto.mp_head_i = function () {
		var t = new eui.Image();
		this.mp_head = t;
		t.left = 165;
		t.source = "def_head_png";
		t.top = 238;
		return t;
	};
	_proto._Image2_i = function () {
		var t = new eui.Image();
		t.left = 157;
		t.source = "head_two_png";
		t.top = 232;
		return t;
	};
	_proto.mp_nick_i = function () {
		var t = new eui.Label();
		this.mp_nick = t;
		t.fontFamily = "SimHei";
		t.left = 259;
		t.text = "";
		t.textColor = 0x000000;
		t.top = 247;
		return t;
	};
	_proto.mp_uid_i = function () {
		var t = new eui.Label();
		this.mp_uid = t;
		t.left = 260;
		t.text = "ID:";
		t.textColor = 0x000000;
		t.top = 286;
		return t;
	};
	_proto.mp_amount_i = function () {
		var t = new eui.Label();
		this.mp_amount = t;
		t.anchorOffsetX = 0;
		t.fontFamily = "SimHei";
		t.right = 213;
		t.size = 60;
		t.text = "0";
		t.textAlign = "center";
		t.textColor = 0xff0000;
		t.top = 362;
		t.width = 180;
		return t;
	};
	_proto.mp_obtain_i = function () {
		var t = new eui.Button();
		this.mp_obtain = t;
		t.bottom = 465;
		t.horizontalCenter = 0.5;
		t.label = "";
		t.skinName = myprize_skin$Skin7;
		return t;
	};
	_proto.mp_notes_i = function () {
		var t = new eui.Button();
		this.mp_notes = t;
		t.bottom = 339;
		t.label = "";
		t.left = 122;
		t.skinName = myprize_skin$Skin8;
		return t;
	};
	_proto.mp_receive_i = function () {
		var t = new eui.Button();
		this.mp_receive = t;
		t.bottom = 339;
		t.label = "";
		t.right = 123;
		t.skinName = myprize_skin$Skin9;
		return t;
	};
	return myprize_skin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/window/obainlist.exml'] = window.obainlist_skin = (function (_super) {
	__extends(obainlist_skin, _super);
	var obainlist_skin$Skin10 = 	(function (_super) {
		__extends(obainlist_skin$Skin10, _super);
		function obainlist_skin$Skin10() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = obainlist_skin$Skin10.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "close_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return obainlist_skin$Skin10;
	})(eui.Skin);

	function obainlist_skin() {
		_super.call(this);
		this.skinParts = ["ob_close","ob_data"];
		
		this.height = 1206;
		this.width = 750;
		this.elementsContent = [this._Rect1_i(),this._Image1_i(),this.ob_close_i(),this._Scroller1_i()];
	}
	var _proto = obainlist_skin.prototype;

	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.fillAlpha = 0.5;
		t.height = 1206;
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		t.width = 750;
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.horizontalCenter = 0;
		t.source = "receive_list_png";
		t.top = 122;
		return t;
	};
	_proto.ob_close_i = function () {
		var t = new eui.Button();
		this.ob_close = t;
		t.bottom = 267;
		t.horizontalCenter = 0;
		t.label = "";
		t.skinName = obainlist_skin$Skin10;
		return t;
	};
	_proto._Scroller1_i = function () {
		var t = new eui.Scroller();
		t.anchorOffsetX = 0;
		t.anchorOffsetY = 0;
		t.height = 360;
		t.horizontalCenter = 0;
		t.top = 387;
		t.width = 510;
		t.viewport = this._Group1_i();
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.elementsContent = [this.ob_data_i()];
		return t;
	};
	_proto.ob_data_i = function () {
		var t = new eui.List();
		this.ob_data = t;
		t.itemRendererSkinName = datarow;
		t.left = 0;
		t.top = 0;
		return t;
	};
	return obainlist_skin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/window/record.exml'] = window.record_skin = (function (_super) {
	__extends(record_skin, _super);
	var record_skin$Skin11 = 	(function (_super) {
		__extends(record_skin$Skin11, _super);
		function record_skin$Skin11() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = record_skin$Skin11.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "close_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return record_skin$Skin11;
	})(eui.Skin);

	function record_skin() {
		_super.call(this);
		this.skinParts = ["rec_close","rec_data"];
		
		this.height = 1206;
		this.width = 750;
		this.elementsContent = [this._Rect1_i(),this._Image1_i(),this.rec_close_i(),this._Scroller1_i()];
	}
	var _proto = record_skin.prototype;

	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.fillAlpha = 0.8;
		t.height = 1206;
		t.width = 750;
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.horizontalCenter = 0.5;
		t.source = "record_list_png";
		t.top = 122;
		return t;
	};
	_proto.rec_close_i = function () {
		var t = new eui.Button();
		this.rec_close = t;
		t.bottom = 267;
		t.horizontalCenter = 0;
		t.label = "";
		t.skinName = record_skin$Skin11;
		return t;
	};
	_proto._Scroller1_i = function () {
		var t = new eui.Scroller();
		t.anchorOffsetX = 0;
		t.anchorOffsetY = 0;
		t.height = 360;
		t.horizontalCenter = 0;
		t.top = 387;
		t.width = 510;
		t.viewport = this._Group1_i();
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.elementsContent = [this.rec_data_i()];
		return t;
	};
	_proto.rec_data_i = function () {
		var t = new eui.List();
		this.rec_data = t;
		t.itemRendererSkinName = datarow;
		t.left = 0;
		t.top = 0;
		return t;
	};
	return record_skin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/window/redbag.exml'] = window.redbag_skin = (function (_super) {
	__extends(redbag_skin, _super);
	var redbag_skin$Skin12 = 	(function (_super) {
		__extends(redbag_skin$Skin12, _super);
		function redbag_skin$Skin12() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = redbag_skin$Skin12.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "query_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return redbag_skin$Skin12;
	})(eui.Skin);

	function redbag_skin() {
		_super.call(this);
		this.skinParts = ["showred","rb_light","label","rb_title","image","rb_query","rb_money"];
		
		this.height = 1206;
		this.width = 750;
		this.showred_i();
		this.elementsContent = [this._Rect1_i(),this.rb_light_i(),this.label_i(),this.rb_title_i(),this.image_i(),this.rb_query_i(),this.rb_money_i()];
		
		eui.Binding.$bindProperties(this, ["label"],[0],this._TweenItem1,"target")
		eui.Binding.$bindProperties(this, [0.1],[],this._Object1,"scaleX")
		eui.Binding.$bindProperties(this, [0.1],[],this._Object1,"scaleY")
		eui.Binding.$bindProperties(this, [1],[],this._Object2,"scaleX")
		eui.Binding.$bindProperties(this, [1],[],this._Object2,"scaleY")
		eui.Binding.$bindProperties(this, ["rb_title"],[0],this._TweenItem2,"target")
		eui.Binding.$bindProperties(this, [0.1],[],this._Object3,"scaleX")
		eui.Binding.$bindProperties(this, [0.1],[],this._Object3,"scaleY")
		eui.Binding.$bindProperties(this, [1],[],this._Object4,"scaleX")
		eui.Binding.$bindProperties(this, [1],[],this._Object4,"scaleY")
		eui.Binding.$bindProperties(this, ["image"],[0],this._TweenItem3,"target")
		eui.Binding.$bindProperties(this, [0.1],[],this._Object5,"scaleX")
		eui.Binding.$bindProperties(this, [0.1],[],this._Object5,"scaleY")
		eui.Binding.$bindProperties(this, [1],[],this._Object6,"scaleX")
		eui.Binding.$bindProperties(this, [1],[],this._Object6,"scaleY")
		eui.Binding.$bindProperties(this, ["rb_query"],[0],this._TweenItem4,"target")
		eui.Binding.$bindProperties(this, [0.1],[],this._Object7,"scaleX")
		eui.Binding.$bindProperties(this, [0.1],[],this._Object7,"scaleY")
		eui.Binding.$bindProperties(this, [1],[],this._Object8,"scaleX")
		eui.Binding.$bindProperties(this, [1],[],this._Object8,"scaleY")
		eui.Binding.$bindProperties(this, ["rb_money"],[0],this._TweenItem5,"target")
		eui.Binding.$bindProperties(this, [0.1],[],this._Object9,"scaleX")
		eui.Binding.$bindProperties(this, [0.1],[],this._Object9,"scaleY")
		eui.Binding.$bindProperties(this, [1],[],this._Object10,"scaleX")
		eui.Binding.$bindProperties(this, [1],[],this._Object10,"scaleY")
	}
	var _proto = redbag_skin.prototype;

	_proto.showred_i = function () {
		var t = new egret.tween.TweenGroup();
		this.showred = t;
		t.items = [this._TweenItem1_i(),this._TweenItem2_i(),this._TweenItem3_i(),this._TweenItem4_i(),this._TweenItem5_i()];
		return t;
	};
	_proto._TweenItem1_i = function () {
		var t = new egret.tween.TweenItem();
		this._TweenItem1 = t;
		t.paths = [this._Set1_i(),this._To1_i()];
		return t;
	};
	_proto._Set1_i = function () {
		var t = new egret.tween.Set();
		t.props = this._Object1_i();
		return t;
	};
	_proto._Object1_i = function () {
		var t = {};
		this._Object1 = t;
		return t;
	};
	_proto._To1_i = function () {
		var t = new egret.tween.To();
		t.duration = 250;
		t.props = this._Object2_i();
		return t;
	};
	_proto._Object2_i = function () {
		var t = {};
		this._Object2 = t;
		return t;
	};
	_proto._TweenItem2_i = function () {
		var t = new egret.tween.TweenItem();
		this._TweenItem2 = t;
		t.paths = [this._Set2_i(),this._To2_i()];
		return t;
	};
	_proto._Set2_i = function () {
		var t = new egret.tween.Set();
		t.props = this._Object3_i();
		return t;
	};
	_proto._Object3_i = function () {
		var t = {};
		this._Object3 = t;
		return t;
	};
	_proto._To2_i = function () {
		var t = new egret.tween.To();
		t.duration = 250;
		t.props = this._Object4_i();
		return t;
	};
	_proto._Object4_i = function () {
		var t = {};
		this._Object4 = t;
		return t;
	};
	_proto._TweenItem3_i = function () {
		var t = new egret.tween.TweenItem();
		this._TweenItem3 = t;
		t.paths = [this._Set3_i(),this._To3_i()];
		return t;
	};
	_proto._Set3_i = function () {
		var t = new egret.tween.Set();
		t.props = this._Object5_i();
		return t;
	};
	_proto._Object5_i = function () {
		var t = {};
		this._Object5 = t;
		return t;
	};
	_proto._To3_i = function () {
		var t = new egret.tween.To();
		t.duration = 250;
		t.props = this._Object6_i();
		return t;
	};
	_proto._Object6_i = function () {
		var t = {};
		this._Object6 = t;
		return t;
	};
	_proto._TweenItem4_i = function () {
		var t = new egret.tween.TweenItem();
		this._TweenItem4 = t;
		t.paths = [this._Set4_i(),this._To4_i()];
		return t;
	};
	_proto._Set4_i = function () {
		var t = new egret.tween.Set();
		t.props = this._Object7_i();
		return t;
	};
	_proto._Object7_i = function () {
		var t = {};
		this._Object7 = t;
		return t;
	};
	_proto._To4_i = function () {
		var t = new egret.tween.To();
		t.duration = 250;
		t.props = this._Object8_i();
		return t;
	};
	_proto._Object8_i = function () {
		var t = {};
		this._Object8 = t;
		return t;
	};
	_proto._TweenItem5_i = function () {
		var t = new egret.tween.TweenItem();
		this._TweenItem5 = t;
		t.paths = [this._Set5_i(),this._To5_i()];
		return t;
	};
	_proto._Set5_i = function () {
		var t = new egret.tween.Set();
		t.props = this._Object9_i();
		return t;
	};
	_proto._Object9_i = function () {
		var t = {};
		this._Object9 = t;
		return t;
	};
	_proto._To5_i = function () {
		var t = new egret.tween.To();
		t.duration = 250;
		t.props = this._Object10_i();
		return t;
	};
	_proto._Object10_i = function () {
		var t = {};
		this._Object10 = t;
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.fillAlpha = 0.8;
		t.height = 1206;
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		t.width = 750;
		return t;
	};
	_proto.rb_light_i = function () {
		var t = new eui.Image();
		this.rb_light = t;
		t.horizontalCenter = 0;
		t.source = "light_png";
		t.verticalCenter = 0;
		return t;
	};
	_proto.label_i = function () {
		var t = new eui.Label();
		this.label = t;
		t.bold = true;
		t.fontFamily = "KaiTi";
		t.horizontalCenter = 0.5;
		t.size = 80;
		t.text = "恭喜您";
		t.top = 72;
		return t;
	};
	_proto.rb_title_i = function () {
		var t = new eui.Label();
		this.rb_title = t;
		t.bold = true;
		t.fontFamily = "KaiTi";
		t.horizontalCenter = 1;
		t.size = 60;
		t.text = "获得888元红包";
		t.top = 192;
		return t;
	};
	_proto.image_i = function () {
		var t = new eui.Image();
		this.image = t;
		t.horizontalCenter = 0;
		t.source = "red_bag_png";
		t.verticalCenter = -42;
		return t;
	};
	_proto.rb_query_i = function () {
		var t = new eui.Button();
		this.rb_query = t;
		t.bottom = 211;
		t.horizontalCenter = 0;
		t.label = "";
		t.skinName = redbag_skin$Skin12;
		return t;
	};
	_proto.rb_money_i = function () {
		var t = new eui.Label();
		this.rb_money = t;
		t.horizontalCenter = 0;
		t.size = 80;
		t.text = "888元";
		t.textColor = 0xffec02;
		t.top = 603;
		return t;
	};
	return redbag_skin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/window/rule.exml'] = window.rule_skin = (function (_super) {
	__extends(rule_skin, _super);
	var rule_skin$Skin13 = 	(function (_super) {
		__extends(rule_skin$Skin13, _super);
		function rule_skin$Skin13() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = rule_skin$Skin13.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.percentHeight = 100;
			t.source = "close_png";
			t.percentWidth = 100;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return rule_skin$Skin13;
	})(eui.Skin);

	function rule_skin() {
		_super.call(this);
		this.skinParts = ["rule_close"];
		
		this.height = 1206;
		this.width = 750;
		this.elementsContent = [this._Rect1_i(),this._Image1_i(),this.rule_close_i()];
	}
	var _proto = rule_skin.prototype;

	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.fillAlpha = 0.8;
		t.height = 1206;
		t.width = 750;
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.horizontalCenter = 0.5;
		t.source = "rule_one_png";
		t.top = 150;
		return t;
	};
	_proto.rule_close_i = function () {
		var t = new eui.Button();
		this.rule_close = t;
		t.bottom = 267;
		t.horizontalCenter = 0.5;
		t.label = "";
		t.skinName = rule_skin$Skin13;
		return t;
	};
	return rule_skin;
})(eui.Skin);