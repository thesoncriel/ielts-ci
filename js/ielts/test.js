define(["jquery", "util", "mediaelement", "timer"], function($, util, me, Timer){
	if ($(".test-section").length === 0) return;
	
	var ListeningVideoPlayer = function(){};
	
	ListeningVideoPlayer.prototype = {
		init: function(option, media){
			var mOpt;
			var mDef = {
				startdelay: 5,
				nextdelay: 60 * 3,
				mediatype: "video/mp4",
				filepath: "",
				filepattern: "",
				filemax: 4
			};
			
			mOpt = this.initOptionByElem(option, mDef);
			
			if (!mOpt.filePattern){
				mOpt.filePattern = this.jqElem.children("source").attr("src");
			}
			
			this.option = mOpt;
			this.fileIndex = 0;
			
			this.initPlayer(media);
			
			return this;
		},
		initOptionByElem: function(elem, mOpt){
			var jqElem = $(elem),
				val;
				
			for(var name in mOpt){
				if (jqElem.data(name) !== undefined){
					val = jqElem.data( name );
					if (util.isNumeric( mOpt[ name ] ) === true){
						mOpt[ name ] = Number( val );
					}
					else{
						mOpt[ name ] = val;
					} 
				}
			}
			
			this.jqElem = jqElem;
			
			return mOpt;
		},
		initPlayer: function(media){
			var self = this;
			var player = media;
			var jqPlayer = $(player);
			
			player.addEventListener("loadeddata", function(e){
				var delay = 0;
				
				if (self.fileIndex === 0){
					delay = self.option.startdelay;
				}
				else{
					delay = self.option.nextdelay;
				}
				console.log(delay + "초 딜레이 시작");
				
				setTimeout(function(){
					console.log(delay + "초 딜레이 끗");
					player.play();
				}, delay * 1000);
				
				self.fileIndex++;
			});
			
			player.addEventListener("ended", function(){
				console.log("끗", self.fileIndex, self.option.filemax);
				if (self.fileIndex < self.option.filemax){
					self.applySource();
				}
			});
			
			this.player = player;
			this.applySource();
		},
		
		getFilePath: function(){
			var sPath = this.option.filepath;
			var sFilePattern = this.option.filepattern;
			var fileIndex = this.fileIndex;
			
			return sPath + sFilePattern.replace("#", fileIndex + 1 );
		},
		
		applySource: function(src){
			var player = this.player;
			var sType = this.option.mediatype;
			var sFile = this.getFilePath();
			
			console.log("applySource", sFile );
			
			//try{player.get(0).src = "";}catch(e){}
			console.log(player);
			player.setSrc( sFile );
			/*player.innerHtml = "";
			player.innerHtml =
				'<source src="' + sFile + '" type="' + sType + '"></source>' 
			;
			*/
		}
	};
	
	var SubjectTestController = function(){};
	
	SubjectTestController.prototype = {
		init: function(){
			var jqForm = $("form");
			
			jqForm.submit({self: this}, function(e){
				var self = e.data.self,
					jqForm = $(this),
					mValidReport = self.testValid(jqForm),
					statusCurrent = jqForm.find("input[name='status_current']").val();
					isFinish = true;
					
				if ((statusCurrent !== "E") && (mValidReport.valid === false)){
					isFinish = confirm( "You have not answered : " + mValidReport.noAnswered + ".\r\nReally Finish?" );
				}
				
				return isFinish;
			});
			
			this.jqForm = jqForm;
			this.initSubObject();
			
			return this;
		},
		
		initSubObject: function(){
			this.initTimer();
			this.initMediaElement();
			this.initAnswerSheet();
		},
		
		initTimer: function(){
			var self = this,
				timer = new Timer().init( this.jqForm.find("[data-rule='timer']") );
			
			timer.tick(function(e){
				e.sender.toTimeString(true);
			});
			timer.subtick(function(e){
				//console.log("subtick!!", e);
			});
			timer.timeout(function(e){
				self.overTest();
			});
			
			this.timer = timer;
		},
		
		initMediaElement: function(){
			var self = this;
			var jqVideo = $("video");
			
			try{
				this.me = new MediaElementPlayer("#" + jqVideo.get(0).id, {
					/*
				    // if the <video width> is not specified, this is the default
				    defaultVideoWidth: 480,
				    // if the <video height> is not specified, this is the default
				    defaultVideoHeight: 270,
				    // if set, overrides <video width>
				    videoWidth: -1,
				    // if set, overrides <video height>
				    videoHeight: -1,
				    // width of audio player
				    audioWidth: 400,
				    // height of audio player
				    audioHeight: 30,
				    // initial volume when the player starts
				    startVolume: 0.8,
				    // useful for <audio> player loops
				    loop: false,
				    // enables Flash and Silverlight to resize to content size
				    enableAutosize: true,
				    // the order of controls you want on the control bar (and other plugins below)
				    //features: ['playpause','progress','current','duration','tracks','volume','fullscreen'],
				    // Hide controls when playing and mouse is not over the video
				    alwaysShowControls: false,
				    // force iPad's native controls
				    iPadUseNativeControls: false,
				    // force iPhone's native controls
				    iPhoneUseNativeControls: false, 
				    // force Android's native controls
				    AndroidUseNativeControls: false,
				    // forces the hour marker (##:00:00)
				    alwaysShowHours: false,
				    // show framecount in timecode (##:00:00:00)
				    showTimecodeFrameCount: false,
				    // used when showTimecodeFrameCount is set to true
				    framesPerSecond: 25,
				    // turns keyboard support on and off for this instance
				    enableKeyboard: true,
				    // when this player starts, it will pause other players
				    pauseOtherPlayers: true,
				    // array of keyboard commands
				    keyActions: []
				 	*/
				 	success: function(media, node, player) {
				 		/*
						var events = ['loadstart', 'play','pause', 'ended'];
						
						for (var i=0, il=events.length; i<il; i++) {
							
							var eventName = events[i];
							
							media.addEventListener(events[i], function(e) {
								$('#output').append( $('<div>' + e.type + '</div>') );
							});
							
						}
						*/
						
						//console.log("????", self.me, media );
						
						self.player = new ListeningVideoPlayer().init( jqVideo, media );
					}
				});
			}
			catch(e){}

			//console.log("me", this.me);			
		},
		
		initAnswerSheet: function(){
			var self = this,
				jqElem = $("[data-rule='answersheet']"),
				jqForm = $( jqElem.data("form") );
			
			jqElem.find("input").change({self: this, jqForm: jqForm}, function(e){
				var jqInput = $(this),
					jqForm = e.data.jqForm,
					action = jqForm.attr("action"),
					name = jqInput.attr("name"),
					mParam,
					num = name.replace("answer", ""),
					val = jqInput.val();
				
				if (jqForm.length === 0){
					return;
				}
				
				jqForm.find("input[name='num']").val(num);
				jqForm.find("input[name='val']").val(val);
				
				mParam = util.serializeMap( jqForm );
				
				$.post( action, mParam, function(rawData){
					console.log("answer success.");
				});
				
				e.data.self.timer.resetSubTick();// 한번 보냈으니 이건 다시 세게...
			});
			
			this.jqAnswerSheet = jqElem;
		},
		
		radioChecked: function (jqForm, name){
			var jqRadio = jqForm.find("input[type='radio'][name='" + name + "']"),
				checked = false;
			
			jqRadio.each(function(index){
				if (this.checked === true){
					checked = true;
				}
			});
			
			return checked;
		},
		testValid: function (jqForm){
			var self = this,
				jqInputList = jqForm.find("input[name^='answer']"),
				jqThis,
				sType = "",
				sName = "",
				sNameCache = "",
				bValChecked = true,
				bNowChecked = false,
				aNoAnswered = [],
				iQuestionNum = 1;
			
			jqInputList.each(function(index){
				jqThis = $(this);
				sType = jqThis.attr("type");
				sName = jqThis.attr("name");
				
				if (sType === "radio"){
					if (sNameCache === sName){
						return;
					}
					sNameCache = sName;
					bNowChecked = self.radioChecked( jqForm, sName );
				}
				else{
					bNowChecked = (jqThis.val().length > 0);
				}
				
				if (bNowChecked === false){
					aNoAnswered.push( iQuestionNum );
				}
				
				bValChecked = bValChecked && bNowChecked;
				
				iQuestionNum++;
			});
			
			return {
				valid: 			bValChecked,
				noAnswered: 	aNoAnswered
			};
		},
		
		overTest: function(){
			var jqAnswerSheet = this.jqAnswerSheet;
			
			jqAnswerSheet.children().eq(0).remove();
			jqAnswerSheet.find(".alert").hide().removeClass("hidden").fadeIn();
		}
	};// SubjectTestController [end]
	
	
	
	
	
	$(function(){
		var ctrl = new SubjectTestController().init();
		
		window.ctrl = ctrl;
	});// onLoad [end]
	
	
});// define [end]
