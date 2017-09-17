define(["jquery", "util"], function($, util){
	var Timer = function() {};
	
	Timer.prototype = {
		init: function(option){
			var mOpt = {
				curr: 0,
				limit: 60,
				interval: 1000,	// 주 타이머 주기 (milisecond)
				subInterval: 5,  // 부 타이머 주기 (second)
				decrease: "",
				autostart: false,
				timeouttext: "Time Out",
				colonClass: "timer-colon"
			};
			
			if (option instanceof jQuery){
				this.opt = this.initOptionByElem( option, mOpt );
			}
			else{
				this.opt = $.extend(mOpt, option);
			}
			
			this.limit = 0;
			this.curr = 0;
			this.subCurr = 0;
			
			if (this.opt.autostart){
				this.start( this.opt.limit, this.opt.curr );
			}
			
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
		
		
		
		start: function(limit, curr){
			var self = this;
			
			this.limit = this.opt.limit || 60;
			this.curr = curr || 0;
			this.ontimerstart({sender: this, curr: this.curr, limit: this.limit});
			this._onInterval();
		},
		
		stop: function(){
			clearTimeout( this.timeoutid );
		},
		
		hasTimeOut: function(){
			return this.curr >= this.limit;
		},
		
		getCurr: function(){
			return this.curr;
		},
		
		getLimit: function(){
			return this.limit;
		},
		
		resetSubTick: function(){
			this.subCurr = 0;
		},
		
		toTimeString: function(toDecrease, colon){
			var curr = this.curr,
				limit = this.limit,
				date = new Date( ( (toDecrease)? limit - curr : curr ) * 1000 ),
				iSec = date.getSeconds(),
				iMin = date.getMinutes(),
				sSec = util.fillDigitNum( iSec, 2 ),
				sMin = util.fillDigitNum( iMin, 2 ),
				sColon = colon || ":";
				
			return sMin + sColon + sSec;
		},
		
		apply: function(){
			if (this.jqElem){
				this.jqElem.html( 
					this.toTimeString( 
						this.opt.decrease === "",
						'<span class="' + this.opt.colonClass + '">:</span>' ) );
				
			}
		},
		
		_onInterval: function(){
			var self = this,
				isNotTimeOut = this.curr < this.limit;
			
			if (isNotTimeOut){
				this.timeoutid = setTimeout(function(){
					self._onInterval();
				}, this.opt.interval);
				 
				this.ontick({sender: this, curr: this.curr, limit: this.limit});
				this.curr++;
			}
			else{
				if (this.ontimeout({sender: this, curr: this.curr, limit: this.limit}) !== false){
					if (this.opt.timeouttext !== ""){
						alert( this.opt.timeouttext );
					}
				}
				this.stop();
			}
			
			if (this.subCurr < this.opt.subInterval){
				this.subCurr++;
			}
			else{
				this.subCurr = 1;
				this.onsubtick({sender: this, curr: this.curr, subCurr: this.subCurr, limit: this.limit});
			}
			
			this.apply();
		},
		
		_subtick: null,
		subtick: function(callback){ this._subtick = callback; },
		onsubtick: function(e){ try{ this._subtick(e); }catch(ex){} },
		
		_tick: null,
		tick: function(callback){ this._tick = callback; },
		ontick: function(e){ try{ this._tick(e); }catch(ex){} },
		
		_timerstart: null,
		timerstart: function(callback){ this._timerstart = callback; },
		ontimerstart: function(e){ try{ this._timerstart(e); }catch(ex){} },
		
		_timeout: null,
		timeout: function(callback){ this._timeout = callback; },
		ontimeout: function(e){ try{ return this._timeout(e); }catch(ex){} }
	};// Timer [end]
	
	return Timer;
});