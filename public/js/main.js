var winSize;
var headSize;

/**
 * Development JavaScript
 */

function warnUser( text, url ) {
	
	var answer = confirm(text);
	
	if (answer) {
		if (isset(url)) {
			window.location = url;
		} else {
			return true;
		}
	} else {
		return false;
	}
	
}

function loadContent( target, url, callback ) {
	new Ajax.Updater( target, url, {
		parameters: { },
		evalScripts: true,
		onSuccess: function(response) { 
			if (isset(callback)) { eval(callback); }
			return true;
		},
		onFailure: function(t) { 
        	$('dialogtitle').update('Loading content failed!');
        	$('dialogcontent').update(t.responseText);
        	showDialog('dialogtitle', 'dialogcontent');
        	return false;
        },
	});
}

function gainattention(which) {
	which.addClassName('highlight');
}

function loseattention(which) {
	which.removeClassName('highlight');
}

function dcCheckIn( docform ) {
	var token = $F(docform.token);
	if (token == "none") {
		if (!warnUser('Check this visitor in without Access Token?')) {
			return false;
		}
	}
	var idtype = $F(docform.idtype);
	if (idtype == "none") {
		alert('No ID type chosen!');
		return false;
	}
	var idnumber = $F(docform.idnumber);
	if (idnumber == "") {
		alert('No ID number noted!');
		return false;
	}
	var inorout = $F(docform.inorout);
	var rrid = $F(docform.rrid);
	if (submitAdmin( 'requests', rrid, token, idtype, idnumber, inorout )) {
		return true;
	}
	return false;
};

function dcCheckOut( form ) {
	var token = $F(form.token);
	var inorout = $F(form.inorout);
	var rrid = $F(form.rrid);
	if (submitAdmin( 'requests', rrid, token, null, null, inorout )) {
		return true;
	}
	return false;
};

function processShipment( form ) {
	var sid = $F(form.sid);
	if (isset(form.cid)) {
		var cid = $F(form.cid);
		if (cid == "none") {
			alert("Collector selection is mandatory!");
			return false;
		}
	}
	var url = '/dcxsadmin/update';
	new Ajax.Request( url, {
		parameters: { updatetype: 'shipment',
					  id: sid,
					  contact: (isset(cid))?(cid):(false) },
		onFailure: function(response) { alert ('Something went wrong, your registration did not save.\nPlease report error to support.');
										document.getElementById('ajaxdebug').innerHTML = response.responseText;
										document.getElementById('ajaxdebug').setStyle({ display: 'block' });
										return true; },
		onSuccess: function(response) { window.location.reload();
										return true; }
	});
	return true;
};

function deAssociate( controller, aid ) {
	var url = '/' + controller + '/update';
	new Ajax.Request( url, {
		parameters: { updatetype: 'deassociation',
			  		  id: aid },
		onFailure: function(response) { alert ('Something went wrong, no changes where saved.\nPlease report error to support.');
										document.getElementById('ajaxdebug').innerHTML = response.responseText;
										document.getElementById('ajaxdebug').setStyle({ display: 'block' });
										return false; },
		onSuccess: function(response) { window.location.reload();
										return true; }
	});
	return false;
}

function associationUpdate( controller, el, cid ) {
	var url = '/' + controller + '/update';
	new Ajax.Updater( 'association', url, {
		parameters: { updatetype: 'replassociation',
					  contact_details_id: cid,
					  company_details_id: $F(el) },
		onFailure: function(response) { alert ('Something went wrong. Nothing saved!\nPlease report error to support.');
										document.getElementById('ajaxdebug').innerHTML = response.responseText;
										document.getElementById('ajaxdebug').setStyle({ display: 'block' });
										return false; }
	});
	return false;
}

function updateAssociations( controller, form ) {
	if ($F(form.company_details_id) == "none") {
		alert('No company selected!');
		return false;
	}
	var url = '/' + controller + '/update';
	var target = (controller == "dcxs")?('custassociation'):('association');
	new Ajax.Request( url, {
		parameters: { updatetype: target,
			  		  company: $F(form.company_details_id),
			  		  contact: $F(form.contact_details_id) },
		onFailure: function(response) { alert ('Something went wrong, your choice did not save.\nPlease report error to support.');
										document.getElementById('ajaxdebug').innerHTML = response.responseText;
										document.getElementById('ajaxdebug').setStyle({ display: 'block' });
										return false; },
		onSuccess: function(response) { window.location.reload();
										return true; }
	});
	return false;
}

function submitAdmin( target, srrid, stoken, sidtype, sidnumber, sinorout ) {
	var url = '/dcxsadmin/update';
	new Ajax.Request( url, {
		parameters: { updatetype: target,
					  token: stoken,
					  rrid: srrid,
					  idtype: sidtype,
					  idnumber: sidnumber,
					  inorout: sinorout },
		onFailure: function(response) { alert ('Something went wrong, your registration did not save.\nPlease report error to support.');
										document.getElementById('ajaxdebug').innerHTML = response.responseText;
										document.getElementById('ajaxdebug').setStyle({ display: 'block' });
										return true; },
		onSuccess: function(response) { window.location.reload();
										return true; }
	});
	return true;
}

function isset() {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: FremyCompany
    // +   improved by: Onno Marsman
    // +   improved by: Rafał Kukawski
    // *     example 1: isset( undefined, true);
    // *     returns 1: false
    // *     example 2: isset( 'Kevin van Zonneveld' );
    // *     returns 2: true

  var a = arguments,
    l = a.length,
    i = 0,
    undef;

  if (l === 0)
  {
    throw new Error('Empty isset');
  }

  while (i !== l)
  {
    if (a[i] === undef || a[i] === null)
    {
      return false;
    }
    i++;
  }
  return true;
}

function isChecked( el ) {
	if (el.checked) {
		return true;
	}
	return false;
}

function getCheckedValue(radio_group) {
	for (var i = 0; i < radio_group.length; i++) {
        var button = radio_group[i];
        if (button.checked) {
        	return button.value;
        }
    }
    return "";
}


function accountUpdate( form, controller, action ) {
	var pwdtype = (isset (document.getElementById('changepassword')))?('changepassword'):('generatepassword');
	var passwd = (isset (document.getElementById('generatepassword')))?(document.getElementById('generatepassword')):(document.getElementById('changepassword'));
	
	var url = '/' + controller + '/update';
	new Ajax.Request( url, {
		parameters: { updatetype: 'account',
					  id: $F('account_id'),
					  contact_details_id: $F('contact_details_id'),
					  login: $F('login'),
					  password: (isChecked( passwd ))?(pwdtype):(false),
					  role: $F('role'),
					  active: (getCheckedValue(document.forms.accounts.elements.active) != '')?(getCheckedValue(document.forms.accounts.elements.active)):('1') },
		onFailure: function(response) { alert ('Something went wrong. Nothing saved!\nPlease report error to support.');
										document.getElementById('ajaxdebug').innerHTML = response.responseText;
										document.getElementById('ajaxdebug').setStyle({ display: 'block' });
										return false; },
		onSuccess: function(response) { window.location = '/' + controller + '/' + action + '/type/contact/target/' + $F('contact_details_id');
										return false; }
	});
	return false;
}

function whitelistUpdate( controller, el, cid ) {
	var url = '/' + controller + '/update';
	new Ajax.Updater( 'whitelistsave', url, {
		parameters: { updatetype: 'whitelist',
					  contact_details_id: cid,
					  active: $F(el) },
		onFailure: function(response) { alert ('Something went wrong. Nothing saved!\nPlease report error to support.');
										document.getElementById('ajaxdebug').innerHTML = response.responseText;
										document.getElementById('ajaxdebug').setStyle({ display: 'block' });
										return false; }
	});
	return false;
}

function updateContacts( el, controller ) {
	var url = '/' + controller + '/update';
	new Ajax.Updater( 'contacts', url, {
		parameters: { updatetype: 'contacts',
					  company_details_id: el.value },
		onFailure: function(response) { alert ('Something went wrong, please report error to support.');
										document.getElementById('ajaxdebug').innerHTML = response.responseText;
										document.getElementById('ajaxdebug').setStyle({ display: 'block' });
										return false; }
	});
	return false;
}

function updateReceived( el ) {
	var url = '/dcxs/update';
	new Ajax.Updater( 'received', url, {
		parameters: { updatetype: 'received',
					  company_details_id: el.value },
		onFailure: function(response) { alert ('Something went wrong, please report error to support.');
										document.getElementById('ajaxdebug').innerHTML = response.responseText;
										document.getElementById('ajaxdebug').setStyle({ display: 'block' });
										return false; }
	});
	return false;
}

var globalTimerId = -1;

function keyDownSelect( controller, target, searchval ) {
    if (globalTimerId != -1) {
        clearTimeout(globalTimerId);
     }

    globalTimerId = setTimeout('updateSelect( \''+controller+'\', \''+target+'\', \''+searchval+'\' )', 750);
}

function updateSelect( controller, target, searchval ) {
	globalTimerId = -1;
	var url = '/' + controller + '/update';
	new Ajax.Updater( target, url, {
		parameters: { updatetype: 'select',
					  refine: target,
					  like: searchval },
		onFailure: function(response) { document.getElementById('ajaxdebug').innerHTML = response.responseText;
										document.getElementById('ajaxdebug').setStyle({ display: 'block' });
										return false; }
	});
	return false;
}

function keyDownAccount( controller, target, searchval ) {
    if (globalTimerId != -1) {
        clearTimeout(globalTimerId);
     }

    globalTimerId = setTimeout('checkLogin( \''+controller+'\', \''+target+'\', \''+searchval+'\' )', 750);
}

function checkLogin( controller, target, searchval ) {
	globalTimerId = -1;
	var url = '/' + controller + '/update';
	new Ajax.Updater( target, url, {
		parameters: { updatetype: 'login',
					  login: searchval },
		onFailure: function(response) { document.getElementById('ajaxdebug').innerHTML = response.responseText;
										document.getElementById('ajaxdebug').setStyle({ display: 'block' });
										return false; }
	});
	return false;
}

/**
 * Get window dimensions.
 */
function getWindowSize(w) {
	var width, height;
	w = w ? w : window;
	width = w.innerWidth || (w.document.documentElement.clientWidth || w.document.body.clientWidth);
	height = w.innerHeight || (w.document.documentElement.clientHeight || w.document.body.clientHeight);

	return { width: width, height: height }; 
}

/*
 * Show a dialog, within the confines of the viewport.
 * Takes the id's where to get its contents from as arguments.
 */
function showDialog( title, content ) {
	
	vpWidth=winSize.width;
	vpHeight=winSize.height;
	
	// get dialog's width and height
	dialogWidth=$('dialog').getWidth();
	dialogHeight=$('dialog').getHeight();
	
	// calculate position
	dialogTop = (vpHeight/2) - (dialogHeight/2);
	dialogLeft = (vpWidth/2) - (dialogWidth/2);
	
	//Position the Dialog
	if (dialogTop < 0) {
		$('dialog').style.top = "0px";
	} else {
		$('dialog').style.top =dialogTop+"px";
	}
	$('dialog').style.left =dialogLeft+"px";
	
	if (vpHeight < 337) {
		$('dialog').style.height = vpHeight+"px";
		$('dialogcontent').style.height = (vpHeight-37)+"px";
	}
	
	if (isset(title)) {
		$('dialogtitle').innerHTML = $(title).innerHTML;
	}
	if (isset(content)) {
		$('dialogcontent').innerHTML = $(content).innerHTML;
	}
	
	$(dialog).setOpacity(0);
	$(dialog).setStyle({visibility: 'visible'});
	new Effect.Opacity(
		dialog, { 
	      from: 0.0, 
	      to: 1.0,
	      duration: 0.5
	   }
	);
	
}

function hideDialog( dialog ) {
	new Effect.Opacity(
		dialog, { 
	      from: 1.0, 
	      to: 0.0,
	      duration: 0.5,
	      
	   }
	);
	window.setTimeout('$(dialog).setStyle({visibility: \'hidden\'})', 750);
}

/*
 * Show a dialog, within the confines of the viewport.
 * Takes the id's where to get its contents from as arguments.
 */

var globalBoxVisible = false;
var expTime = '750';
var updTime = '250';
var gExpandTimerId = -1;
var gUpdateTimerId = -1;
var editInHoverActive = -1;

function expandOnHover( id, target, url, hWidth, hHeight ) {
	
	$(id).addClassName('arrowleft');
	
	if (editInHoverActive != -1) {
		return;
	}
	
	if (gUpdateTimerId != -1) {
        clearTimeout(gUpdateTimerId);
	}
	
	if (gExpandTimerId != -1) {
        clearTimeout(gExpandTimerId);
	}
	
	var siteHeader = ($('siteheader').getHeight()+20);
	
	$(target).setStyle({ top: siteHeader+'px' });
	
	if (isset(hWidth)) {
		$(target+'content').setStyle({ width: hWidth+'px' });
		$(target).setStyle({ width: hWidth+'px' });
	}
	if (isset(hHeight)) {
		$(target+'content').setStyle({ height: hHeight+'px' });
		$(target).setStyle({ height: hHeight+'px' });
	} else {
		hHeight = $(target).getHeight();
	}
	
	if ((siteHeader+hHeight) > winSize.height) {
		var resized = (winSize.height-siteHeader-20);
		$(target+'content').setStyle({ height: resized+'px' });
		$(target).setStyle({ height: resized+'px' });
	}
	
	if (globalBoxVisible === false) {
	
		gExpandTimerId = setTimeout('expandHover( \'' + id + '\', \'' + target + '\', \'' + url + '\' )', expTime);
		
	} else {
		
		gUpdateTimerId = setTimeout('updateHover( \'' + id + '\', \'' + target + '\', \'' + url + '\' )', updTime);
		
	}
}

function expandHover( id, target, url ) {
	
	if (globalBoxVisible !== false) {
		$(globalBoxVisible).removeClassName('stickyarrowleft');
	}
	$(id).addClassName('stickyarrowleft');
	
    $(target).setOpacity(0);
	$(target).setStyle({visibility: 'visible'});
	new Effect.Opacity(
		target, { 
	      from: 0.0, 
	      to: 1.0,
	      duration: 0.4
	   }
	);
	
	Event.observe($('viewport'), 'click', respondToClick);
	Event.observe($('hoverclose'), 'click', respondToClick);
	
	loadContent( target + 'content', url );

	globalBoxVisible = id;

}

function updateHover( id, target, url ) {
	
	if (globalBoxVisible !== false) {
		$(globalBoxVisible).removeClassName('stickyarrowleft');
	}
	$(id).addClassName('stickyarrowleft');
	
	loadContent( target + 'content', url );

	globalBoxVisible = id;

}

function editInHover( id, target, url ) {
	
	$('viewport').stopObserving('click');
	
	if (globalBoxVisible !== false) {
		$(globalBoxVisible).removeClassName('stickyarrowleft');
	}
	$(id).addClassName('stickyarrowleft');
	
	if (globalBoxVisible === false) {
		
		clearTimeout(gExpandTimerId);
		
		$(target).setOpacity(0);
		$(target).setStyle({visibility: 'visible'});
		new Effect.Opacity(
			target, { 
		      from: 0.0, 
		      to: 1.0,
		      duration: 0.4
		   }
		);
		
		Event.observe($('hoverclose'), 'click', respondToClick);
		
	}
	
	loadContent( target + 'content', url );

	globalBoxVisible = id;
	editInHoverActive = id;
	
}

function hideHover( target ) {
	
	if (globalBoxVisible !== false) {
		
		$(globalBoxVisible).removeClassName('stickyarrowleft');
		
		new Effect.Opacity(
			target, { 
		      from: 1.0, 
		      to: 0.0,
		      duration: 0.25,
		   }
		);
		window.setTimeout('$(\'' + target + '\').setStyle({visibility: \'hidden\'})', 250);
		
		$('hoverclose').stopObserving('click');
		
		globalBoxVisible = false;
		editInHoverActive = -1;
		
	}
	
}

function cancelExpand( id, target ) {
	
	$(id).removeClassName('arrowleft');
	
	if (globalBoxVisible === false && gExpandTimerId != -1) {
        clearTimeout(gExpandTimerId);
	}
	
	
}

//Callback function to handle the event.
function respondToClick(e) {
	
	var element = Event.element(e);
	element.stopObserving('click', hideHover( 'hoverbox' ));
    
}

function checkAll() {
	
	var boxes = $$("#boxed");
	
	boxes.each(function(box){
		if (box.checked) {
			box.checked = false;
		} else {
			box.checked = true;
		}
	});
	
}

function submitForm( form, target ) {
	
	/*
	var elements = form.getElements();
	
	elements.each(function(el){
		if (isset(el)) {
			alert( el.inspect() + ' has value ' + el.value + ' and is of type ' + el.type );
		}
	});
	
	var radios = $$("[id^=radz-]");
	
	radios.each(function(radio){
		if (radio.checked) {
			alert( radio.value );
		}
	});
	
	form.action = '/save/bla';
	form.submit();
	*/
	
	form.action = target;
	
	form.request({
        onFailure: function(t) { 
        	$('dialogtitle').update('Form Submit Error!');
        	$('dialogcontent').update(t.responseText);
        	showDialog('dialogtitle', 'dialogcontent');
        },
        onSuccess: function(t) {
        	$('dialogtitle').update('Form Submit Success!');
        	$('dialogcontent').update(t.responseText);
        	showDialog('dialogtitle', 'dialogcontent');
        }
    });
	
}

function jsToggle ( el, effect ) {
	
	if(!isset(el) || !isset(effect)) { return false; }
	
	Effect.toggle(el, effect, { duration: 1.0 });
	
}

function togglePreference( source, item ) {
	
	var visible = Cookie.get(item, true);
	if (visible) {
		$(source).removeClassName('stuck');
		$(source).addClassName('unstuck');
	} else {
		$(source).removeClassName('unstuck');
		$(source).addClassName('stuck');
	}
	visible = !visible;
	Cookie.set(item, visible);
	
}

var Cookie = {

	key : 'preferences',

	set : function(key, value) {
		var cookies = this.getCookies();
		cookies[key] = value;
		var src = Object.toJSON(cookies).toString();
		this.setCookie(this.key, src);
	},

	get : function(key) {
		if (this.exists(key)) {
			var cookies = this.getCookies();
			return cookies[key];
		}
		if (arguments.length == 2) {
			return arguments[1];
		}
		return;
	},

	exists : function(key) {
		return key in this.getCookies();
	},

	clear : function(key) {
		var cookies = this.getCookies();
		delete cookies[key];
		var src = Object.toJSON(cookies).toString();
		this.setCookie(this.key, src);
	},

	getCookies : function() {
		return this.hasCookie(this.key) ? this.getCookie(this.key).evalJSON()
				: {};
	},

	hasCookie : function(key) {
		return this.getCookie(key) != null;
	},

	setCookie : function(key, value) {
		var expires = new Date();
		expires.setTime(expires.getTime() + 1000 * 60 * 60 * 24 * 365);
		document.cookie = key + '=' + escape(value) + '; expires=' + expires
				+ '; path=/';
	},

	getCookie : function(key) {
		var cookie = key + '=';
		var array = document.cookie.split(';');
		for ( var i = 0; i < array.length; i++) {
			var c = array[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1, c.length);
			}
			if (c.indexOf(cookie) == 0) {
				var result = c.substring(cookie.length, c.length);
				return unescape(result);
			}
			;
		}
		return null;
	}
};
