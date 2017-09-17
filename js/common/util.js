define(["jquery"], function($){
	var util = {
		serializeMap : function( jqElem, arrToOneStr, delimiter ){
	    	var mData = {};
	    	var jqInput = null;
	    	var sName = "";
	    	var sValue = "";
	    	var sType = "";
	    	var sTmp = "";
	    	var isArrToOneStr = $.isArray(arrToOneStr);
	    	var sDelimiter = delimiter || ",";
	    	var mCacheChecked = {};
	    	
	    	jqElem.find("input[type!='button'][type!='submit'][type!='image'], select, textarea").each(function(index){
	    		jqInput = $(this);
	    		
	    		if (jqInput.attr("disabled") !== undefined) return;
	    		
	    		sType = jqInput.attr("type");
	    		
	    		sName = jqInput.attr("name");
	    		sValue = jqInput.val();
	    		
	    		if (mData.hasOwnProperty( sName ) === false){
	    			if ((sType === "checkbox") || (sType === "radio")){
	    				if (this.checked === true){
	    					mData[ sName ] = sValue;
	    				}
	    				else{
	    					mCacheChecked[ sName ] = "";
	    				}
	    			}
	    			else{
	    				mData[ sName ] = sValue;
	    			}
	    		}
	    		else {
	    			if ((sType === "checkbox") || (sType === "radio")){
	    				if (mCacheChecked.hasOwnProperty( sName ) === true){
		    				mData[ sName ] = "";
		    				delete mCacheChecked[ sName ];
		    			}
		    			if (this.checked === false){
	    					sValue = "";
	    				}
	    			}
	    			
	    			if (isArrToOneStr === true){
	    				if (arrToOneStr.indexOf( sName ) >= 0){
	    					mData[ sName ] = mData[ sName ] + sDelimiter + sValue;
	    				}
	    			}
	    			else{
	    				if ($.isArray(mData[ sName ]) === false){
		    				sTmp = mData[ sName ];
		    				mData[ sName ] = [];
		    				mData[ sName ][0] = sTmp;
		    			}
		    			mData[ sName ][ mData[ sName ].length ] = sValue;
	    			}
	    		}
	    	});
	    	
	    	return mData;
		},
		
		fillDigitNum: function (num, digit){
		    var iLen = num.toString().length,
		        iDigit = digit || 2,
		        sVal,
		        sTmp = "";
		    
		    if (iLen === digit){
		        return num;
		    }
		    
		    sVal = num + "";
		    
		    for(var i = iLen; i < iDigit; i++){
		        sTmp += "0";
		    }
		    
		    return sTmp + sVal;
		},
		
		isNumeric: function(n) {
			return !isNaN(parseFloat(n)) && isFinite(n);
		}
	};
	
	return util;
});