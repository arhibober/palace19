/**
* @license
* @brief    metaudio audio and music library
* @author   Levente Hunyadi
* @version  0.8.0
* @remarks  Copyright (C) 2010 Levente Hunyadi
* @remarks  Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
* @see      http://hunyadi.info.hu/projects/metaudio
*/

var metaudio_accordion;
var metaudio_decode;

if (!Array.prototype.map) {
	/**
	* Array map function for those browsers that do not support it natively.
	*/
	Array.prototype.map = function(fun) {
		var t = this;
		var len = t.length;

		var res = new Array(len);
		for (var i = 0; i < len; i++) {
			res[i] = fun.call(this, t[i], i, t);
		}
		return res;
	};
}

(function () {
	//
	// Basic obfuscation functions
	//

	/**
	* Rotates characters by 13 places.
	*/
	function rot13(s) {
		return s.replace(/[a-zA-Z]/g,
			function(c) {
				return String.fromCharCode( (c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c-26 );
			}
		);
	}

	/**
	* Returns a string that comprises of the given character codes.
	*/
	function fromCharCodes(codes) {
		return codes.map(function (code) {
			return String.fromCharCode(code);
		}).join('');
	}

	/**
	* Decodes a UTF-8 string.
	*/
	function utf8_decode(utf8str) {
		var codes = new Array();
		var code, code2, code3, code4, j = 0;
		for (var i = 0; i < utf8str.length; ) {
			code = utf8str.charCodeAt(i++);
			if (code > 127) code2 = utf8str.charCodeAt(i++);
			if (code > 223) code3 = utf8str.charCodeAt(i++);
			if (code > 239) code4 = utf8str.charCodeAt(i++);

			if (code < 128) codes[j++]= code;
			else if (code < 224) codes[j++] = ((code-192) << 6) + code2-128;
			else if (code < 240) codes[j++] = ((code-224) << 12) + ((code2-128) << 6) + code3-128;
			else codes[j++] = ((code-240) << 18) + ((code2-128) << 12) + ((code3-128) << 6) + code4-128;
		}
		return fromCharCodes(codes);
	}

	/**
	* Generates the key string for base64 decoding.
	* @return The string "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".
	*/
	function base64_keystr() {
		var alpha = new Array();
		for (var k = 65; k <= 90; k++) {  // String.fromCharCode('A') = 65 and String.fromCharCode('Z') = 90
			alpha.push(k);
		}
		var alphastr = fromCharCodes(alpha);

		var numeric = new Array();
		for (var k = 48; k <= 57; k++) {  // String.fromCharCode('0') = 48 and String.fromCharCode('9') = 57
			numeric.push(k);
		}
		var numericstr = fromCharCodes(numeric);

		return alphastr + alphastr.toLowerCase() + numericstr + '+/=';
	}

	var keystr = base64_keystr();

	/**
	* Applies the base64 decode algorithm to an ASCII input string.
	*/
	function base64_decode(input) {
		var output = new Array();
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var len = input.length;
		var i = 0;

		while (i < len) {
			enc1 = keystr.indexOf(input.charAt(i++));
			enc2 = keystr.indexOf(input.charAt(i++));
			enc3 = keystr.indexOf(input.charAt(i++));
			enc4 = keystr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output.push(chr1);
			if (enc3 != 64) {
				output.push(chr2);
			}
			if (enc4 != 64) {
				output.push(chr3);
			}
		}
		return fromCharCodes(output);
	}

	/**
	* Decrypts a string obfuscated on the server side.
	*/
	metaudio_decode = function (str) {
		return rot13(utf8_decode(base64_decode(rot13(str))));
	}

	//
	// Accordion
	//

	/** Total time of the animation [ms]. */
	var timeToSlide = 250;
	/** Accordion currently open. */
	var accordionOpen;

	/**
	* Performs an animation of simultaneously opening an accordion and closing another one.
	* @param elem The previous sibling of the accordion in the HTML DOM. Usually, clicking this element triggers showing the accordion content.
	*/
	metaudio_accordion = function (elem) {
		elem = getNext(elem);
		if (elem && elem != accordionOpen) {
			var opening = null;
			if (elem.hasChildNodes()) {
				elem.style.overflow = 'hidden';
				opening = elem;
			}
			var closing = accordionOpen;  // make a local copy of variable
			if (closing) {
				closing.style.overflow = 'hidden';
			}
			setTimeout(function () {
				animate(new Date().getTime(), timeToSlide, closing, opening);
			}, 33);
			accordionOpen = opening;
		}
	}

	/**
	* Next sibling of an element.
	* Text nodes are ignored when finding the next sibling.
	*/
	function getNext(elem) {
		do {
			elem = elem.nextSibling;
		} while (elem && elem.nodeType != 1);
		return elem;
	}

	/**
	* Computed (effective) style of an element.
	*/
	function getStyle(elem, property){
		var value = null;
		if (document.defaultView && document.defaultView.getComputedStyle) {
			value = document.defaultView.getComputedStyle(elem, null).getPropertyValue(property);
		} else if (elem.currentStyle){
			property = property.replace(/\-(\w)/g, function (match, p1){
				return p1.toUpperCase();
			});
			value = elem.currentStyle[property];
		}
		return value;
	}

	/**
	* Accordion animation.
	*/
	function animate(lastTick, timeLeft, closing, opening) {
		var curTick = new Date().getTime();
		timeLeft -= curTick - lastTick;

		if (timeLeft <= 0) {
			if (opening) {
				var openingMaxHeight = parseInt(getStyle(opening, 'max-height'));
				if (!openingMaxHeight) {
					openingMaxHeight = 0;
				}
				opening.style.height = openingMaxHeight + 'px';
				opening.style.overflow = '';
			}
			if (closing) {
				closing.style.display = 'none';
				closing.style.height = '0';
			}
		} else {
			// completion ratio
			var ratio = timeLeft / timeToSlide;

			if (opening) {
				var openingMaxHeight = parseInt(getStyle(opening, 'max-height'));
				if (opening.style.display != 'block') {
					opening.style.display = 'block';
				}
				var height = Math.round((1-ratio) * openingMaxHeight);
				if (openingMaxHeight) {
					opening.style.height = height + 'px';
				}
			}
			if (closing) {
				var closingMaxHeight = parseInt(getStyle(closing, 'max-height'));
				if (closingMaxHeight) {
					closing.style.height = Math.round(ratio * closingMaxHeight) + 'px';
				}
			}

			setTimeout(function () {
				animate(curTick, timeLeft, closing, opening);
			}, 33);
		}
	}
})();