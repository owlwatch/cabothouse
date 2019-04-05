const Cookies = {

	get : function(name){
		var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    	var value = v ? v[2] : null;
		try {
			return JSON.parse(decodeURIComponent(value));
		}catch(e){
			return value;
		}
	},

	set : function(name, value, days){
		var d = new Date;
		value = encodeURIComponent( JSON.stringify(value) );
	    d.setTime(d.getTime() + 24*60*60*1000*days);
	    document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
	},

	delete : function(name){
		this.set(name, '', -1);
	}
};

export default Cookies;
