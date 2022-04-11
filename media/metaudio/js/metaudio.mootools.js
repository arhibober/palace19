/**
* @file
* @brief    metaudio audio and music library
* @author   Levente Hunyadi
* @version  0.8.0
* @remarks  Copyright (C) 2010-2012 Levente Hunyadi
* @remarks  Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
* @see      http://hunyadi.info.hu/projects/metaudio
*/

/*
* metaudio Player: JavaScript sound player for mp3/m4a audio
* Copyright 2010-2012 Levente Hunyadi
*
* metaudio is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* metaudio is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with metaudio.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
* Requires MooTools Core 1.2 or later.
*
* Search for "EDIT OPTIONS" to find out where to modify default settings.
*/

/**
* Interface for metaudio Player.
* The initial interface comprises of a single method to initialize the player, "init". Successful
* initialization exposes the method "create" to create sound objects, and a collection "sounds"
* to iterate over sound objects. Sound objects are backed by a player based on either the HTML5
* audio element, or Flash. The internal implementation is opaque to the user, the same sound object
* interface is exposed:
* # url: string
* # player: unspecified-object
* # play: function () : void
* # stop: function () : void
* # resume: function () : void
* # pause: function () : void
* # getTotal: function () : unspecified-float-type
* # getLoaded: function () : unspecified-float-type
* # getDuration: function () : milliseconds
* # getPosition: function () : milliseconds
* # setPosition: function (milliseconds) : void
*/
window.metaudio = {
	init: function (settings) {
		function addSoundEvents(sound, attrs) {
			var noop = function () { };
			var soundevents = {
				onload: noop,
				onplay: noop,
				onstop: noop,
				onresume: noop,
				onpause: noop,
				onfinish: noop,
				onseek: noop,
				whileplaying: noop,
				whileloading: noop
			};
			for (var evt in soundevents) {
				sound[evt] = attrs[evt] ? attrs[evt] : soundevents[evt];
			}
		}

		// helper element to test for HTML <audio> capabilities
		var html5audio = new Element('audio');
		if (html5audio) {
			// add support for events specific to HTML element <audio>
			['progress','canplay','canplaythrough','play','pause','ended','seeking','timeupdate'].each(function (evt) {
				Element.NativeEvents[evt] = 1;
			});
		}

		var swf = new Swiff(settings.swfurl, {
			id: 'metaudio-player',
			container: 'metaudio-placeholder',
			params: {
				menu: false,
				scale: 'noScale'
			},
			properties: {
				name: 'metaudio-player'  // added for IE compatibility
			}
		});

		if (swf) {
			swf = document.id(swf);  // extend with MooTools methods
		}

		window['metaudio'] = {  // replace metaudio global object
			/**
			* Sounds collection.
			*/
			'sounds': {},

			html5player: {
				/**
				* Creates a new sound instance and adds it to the sounds collection.
				*/
				'create': function (attrs) {
					var player = new Element('audio', {
						src: attrs.url
					});

					// create new sound instance
					var sound = {
						'url': attrs.url,
						/** An HTML <audio> element. */
						'player': player,
						/** True when the sound has been paused and is waiting to be resumed. */
						'paused': false,

						'play': function () {
							player.play();
						},
						'stop': function () {
							player.pause();
						},
						'resume': function () {
							player.play();
						},
						'pause': function () {
							player.pause();
						},
						'getTotal': function () {
							return sound.duration;
						},
						'getLoaded': function () {
							var d = sound.duration;
							if (d) {  // duration is available
								d = 0;
								for (range in sound.buffered) {
									d += range;
								}
							}
							return d;
						},
						'getDuration': function () {
							return player.duration * 1000;
						},
						'getPosition': function () {
							return player.currentTime * 1000;
						},
						'setPosition': function (milliseconds) {
							player.currentTime = milliseconds / 1000;
						}
					};
					addSoundEvents(sound, attrs);

					player.addEvents({
						'progress': function () {
							sound.whileloading();
						},
						/**
						* Fired when a sound has been buffered and is ready to play.
						*/
						'canplay': function () {
							sound.onload();
						},
						/**
						* Fired when a sound has been fully buffered.
						*/
						'canplaythrough': function () {
							sound.whileloading();  // loading is finished
						},
						/**
						* Fired when a sound starts playing or a sound that has been paused resumes playing.
						*/
						'play': function () {
							if (sound.paused) {
								sound.paused = false;
								sound.onresume();
							} else {
								sound.onplay();
							}
						},
						/**
						* Fired when a sound stops playing or a sound that has started playing is paused.
						*/
						'pause': function () {
							sound.paused = true;
							sound.onpause();
						},
						/**
						* Fired when a sound completes playing.
						*/
						'ended': function () {
							sound.paused = false;
							sound.onfinish();
						},
						/**
						* Fired when a new playhead position is set.
						*/
						'seeking': function () {
							sound.onseek(sound.currentTime * 1000);
						},
						/**
						* Fired when the playhead position has changed.
						*/
						'timeupdate': function () {
							sound.whileplaying();
						}
					});

					return sound;
				}
			},

			flashplayer: {
				/**
				* HTML DOM object of the global Flash player instance.
				*/
				'player': swf,
				/**
				* Creates a new sound instance and adds it to the sounds collection.
				*/
				'create': function (attrs) {
					var self = this;
					var player = this.player;

					// create new sound instance
					player.mpCreate(attrs.url);

					var sound = {
						'url': attrs.url,
						'loading': true,
						'playing': false,

						'play': function () {
							player.mpPlay(this.url);
						},
						'stop': function () {
							player.mpStop(this.url);
						},
						'resume': function () {
							player.mpResume(this.url);
						},
						'pause': function () {
							player.mpPause(this.url);
						},
						'getTotal': function () {
							return player.mpGetBytesTotal(this.url);
						},
						'getLoaded': function () {
							return player.mpGetBytesLoaded(this.url);
						},
						'getDuration': function () {
							return player.mpGetDuration(this.url);
						},
						'getPosition': function () {
							return player.mpGetPosition(this.url);
						},
						'setPosition': function (milliseconds) {
							player.mpSetPosition(this.url, milliseconds);
						},
						/**
						* Retrieves peak volume data and saves it in a property.
						*/
						'getPeak': function () {
							return player.mpGetPeak();
						},
						/**
						* Retrieves waveform data and saves it in a property.
						*/
						'getWaveform': function () {
							return player.mpGetWaveform();
						}
					};
					addSoundEvents(sound, attrs);

					window.clearInterval(this.interval);
					this.interval = window.setInterval(function () {
						self.poll();
					}, 100);

					return sound;
				},

				/**
				* Automatically called periodically to help update visual user interface.
				*/
				'poll': function () {
					for (var soundurl in window.metaudio.sounds) {
						var sound = window.metaudio.sounds[soundurl];
						if (sound.playing) {
							sound.whileplaying();
						}
						if (sound.loading) {
							sound.whileloading();
							var total = sound.getTotal();
							var loaded = sound.getLoaded();
							if (total > 0 && loaded >= total) {
								sound.loading = false;
							}
						}
					}
				}
			},

			'create': function (attrs) {
				var self = this;

				/**
				* True if the audio format is supported by the browser via the HTML5 element <audio>.
				* @param {string} format The audio format MIME type.
				*/
				function isAudioFormatSupported(format) {
					return ['probably','maybe'].contains(html5audio.canPlayType('audio/' + format));
				}

				var url = attrs.url;
				var player;
				var audiosupported = /\.ogg$/.test(url) && isAudioFormatSupported('ogg') || /\.(m4a|mp4)$/.test(url) && isAudioFormatSupported('mp4') || /\.mp3$/.test(url) && isAudioFormatSupported('mpeg');
				if (audiosupported && !settings.spectrogram && !settings.peak) {  // use Flash only if absolutely necessary
					player = self.html5player;
				} else if (self.flashplayer.player && /\.(m4a|mp4|mp3)$/.test(url)) {  // visualization features require Flash
					player = self.flashplayer;
				} else if (audiosupported) {  // Flash not supported on platform, disable visualization
					player = self.html5player;
				}

				// add sound to collection
				var sound = player.create(attrs);
				self.sounds[attrs.url] = sound;
				return sound;
			},

			/**
			* Fired when a sound has been buffered and is ready to play.
			* This function is a callback, invoked from ActionScript.
			*/
			'onload': function (url) {
				var sound = this.sounds[url];
				sound.onload();
			},

			/**
			* Fired when a sound starts playing.
			* This function is a callback, invoked from ActionScript.
			*/
			'onplay': function (url) {
				var sound = this.sounds[url];
				sound.playing = true;
				sound.onplay();
			},

			/**
			* Fired when a sound stops playing.
			* This function is a callback, invoked from ActionScript.
			*/
			'onstop': function (url) {
				var sound = this.sounds[url];
				sound.playing = false;
				sound.onstop();
			},

			/**
			* Fired when a sound that has been paused resumes playing.
			* This function is a callback, invoked from ActionScript.
			*/
			'onresume': function (url) {
				var sound = this.sounds[url];
				sound.playing = true;
				sound.onresume();
			},

			/**
			* Fired when a sound that has started playing is paused.
			* This function is a callback, invoked from ActionScript.
			*/
			'onpause': function (url) {
				var sound = this.sounds[url];
				sound.playing = false;
				sound.onpause();
			},

			/**
			* Fired when a sound completes playing.
			* This function is a callback, invoked from ActionScript.
			*/
			'onfinish': function (url) {
				var sound = this.sounds[url];
				sound.playing = false;
				sound.onfinish();
			},

			/**
			* Fired when a new playhead position is set.
			* This function is a callback, invoked from ActionScript.
			* @param pos The new position set.
			*/
			'onseek': function (url, pos) {
				this.sounds[url].onseek(pos);
			},

			/**
			* Pauses playing all sounds except the one specified.
			*/
			'pauseAllBut': function (url) {
				for (var soundurl in this.sounds) {
					if (soundurl != url) {
						this.sounds[soundurl].pause();
					}
				}
			}
		};
	}
};

/**
* Graphical user interface for metaudio Player.
*/
function metaudioPlayer(settings) {
	// --- EDIT OPTIONS BELOW TO MODIFY DEFAULTS --- //
	// set default configuration options
		settings = Object.merge({
		autoPlayNext: false,
		clock: true,
		peak: false,
		spectrogram: false,
		statusbar: true
	}, settings);
	// --- END OF DEFAULT OPTIONS --- //

	/**
	* Visualizes the peak volume over all frequencies.
	* Peak volume data is available for both left and right channels, represented as floating point
	* numbers in between 0.0 and 1.0 inclusive. The peak volume is calculated as the maximum value
	* of the amplitudes in the FFT frequency spectrum per channel. When multiple sounds are playing
	* simultaneously, the values apply to the mixed sound.
	*/
	var PeakDataVisualizer = new Class({
		'initialize': function () {
			this.visualizer = new Element('div', {
				'class': 'metaudio-peak'
			}).adopt(
				new Element('div').adopt(
					// left channel
					new Element('div', {
						'class': 'metaudio-left'
					}),
					// right channel
					new Element('div', {
						'class': 'metaudio-right'
					})
				)
			);
		},

		'toElement': function () {
			return this.visualizer;
		},

		/**
		* @param peakdata An object with properties "left" and "right".
		*/
		'update': function (peakdata) {
			var bars = this.visualizer;
			if (peakdata) {
				bars.getElement('.metaudio-left').setStyle('height', (100*peakdata.left)+'%');
				bars.getElement('.metaudio-right').setStyle('height', (100*peakdata.right)+'%');
			} else {
				bars.getChildren().setStyle('height', 0);
			}
		}
	});

	/**
	* Determines whether the browser has canvas support, required for drawing waveform data.
	*/
	function isCanvasSupported() {
		var canvas = document.createElement('canvas');
		return !!(canvas && canvas.getContext && canvas.getContext('2d'));
	}

	/**
	* Visualizes waveform data sampled at 44.1 kHz.
	* Waveform data is available for both left and right channels, represented as 256-element
	* arrays of floating point numbers between -1.0 and 1.0 inclusive, indicating sound amplitude
	* at each point in time in a 256-element wide window.
	*/
	var Spectrogram = new Class({
		'initialize': function () {
			var self = this;

			// initialize portable canvas support
			var canvasDOM = document.createElement('canvas');
			canvasDOM.width = 256;
			canvasDOM.height = 20;
			var manager = window.G_vmlCanvasManager;
			if (manager) {
				manager.initElement(canvasDOM);
			}

			// initialize spectrogram
			self.spectrogram = new Element('div', {
				'class': 'metaudio-spectrogram',
				'events': {
					'click': function (event) {
						self.spectrogram.toggleClass('metaudio-swap');  // swap left and right channel in spectrogram upon mouse click
						self.update();
						return false;  // do not propagate click event if spectrogram channels are swapped
					}
				}
			}).adopt(canvasDOM);
		},

		'toElement': function () {
			return this.spectrogram;
		},

		/**
		* Updates the spectrogram visualizing waveform data.
		* @param waveformdata An object with properties "left" and "right", each with 256 floating point values in the range [-1;1].
		*/
		'update': function (waveformdata) {
			// get the 2D canvas context for drawing a waveform
			var canvas = this.spectrogram.getElement('canvas');  // the HTML element on which the waveform data is plotted
			var ctx = canvas && canvas.getContext && canvas.getContext('2d');  // a canvas context to use in drawing the waveform
			if (!ctx) {
				return;
			}

			/**
			* Draws a waveform as a connected line series.
			* @param data 256 floating point values in the range [-1;1].
			*/
			function _drawWaveform(data) {
				if (data.length > 0) {
					var size = canvas.getSize();
					var w = size.x;
					var h = size.y;

					ctx.beginPath();
					ctx.moveTo(0,(h*data[0]+h)/2);
					for (var index = 1; index < 256; index++) {
						ctx.lineTo(index*w/256,(h*data[index]+h)/2);
					}
					ctx.stroke();
					ctx.closePath();
				}
			}

			// clear spectrogram
			ctx.clearRect(0,0,canvas.width,canvas.height);

			// show waveform
			if (waveformdata) {  // waveform data available
				ctx.save();

				var swapchannels = canvas.getParent().hasClass('metaudio-swap');

				// draw waveform for left channel
				ctx.lineWidth = '1';
				ctx.strokeStyle = '#9cf';
				_drawWaveform(swapchannels ? waveformdata.right : waveformdata.left);

				// draw waveform for right channel
				ctx.strokeStyle = '#000';
				_drawWaveform(swapchannels ? waveformdata.left : waveformdata.right);

				ctx.restore();
			}
		}
	});

	/**
	* Converts a millisecond value to a time format "mm:ss".
	*/
	function getFormattedTime(milliseconds) {
		var fmt = '-:--';
		if (milliseconds) {
			var minutes = Math.floor(milliseconds / (60 * 1000));
			var seconds = Math.floor((milliseconds % (60 * 1000)) / 1000);
			return minutes + ':' + ('0' + seconds).slice(-2);
		}
		return fmt;
	}

	/**
	* A status bar that displays playhead position as the sound is playing and progress as the sound is loading.
	*/
	var StatusBar = new Class({
		'initialize': function () {
			var self = this;

			// a tooltip displayed when the mouse cursor is positioned over the status bar
			var tooltip = new Element('div', {
				'class': 'metaudio-tooltip'
			}).setStyle('display', 'none').inject(document.body);

			self._statusbar = new Element('div', {
				'class': 'metaudio-statusbar',
				'events': {
					'click': function (event) {
						var pos = self._getPlayheadPosition(event);
						if (self.source) {
							self.source.setPosition(pos);
						}
						return false;
					},
					'mousemove': function (event) {
						var coord = self._statusbar.getCoordinates();
						tooltip.setStyles({
							'left': event.page.x + 20,
							'top': coord.top + coord.height + 10,
							'display': 'block'
						}).set('text', getFormattedTime(self._getPlayheadPosition(event)));
					},
					'mouseout': function () {
						tooltip.setStyle('display', 'none').empty();
					}
				}
			}).adopt(
				self._loading = new Element('div', {
					'class': 'metaudio-loading'
				}),
				self._position = new Element('div', {
					'class': 'metaudio-position'
				})
			)
		},

		'toElement': function () {
			return this._statusbar;
		},

		/**
		* The recording whose data the status bar is visualizing.
		*/
		'source': false,

		/**
		* Updates the progress indicator, showing the proportion of the recording already loaded.
		* @param sound A sound object.
		*/
		'updateLoaded': function () {
			var self = this;

			var total = self.source ? self.source.getTotal() : 0;
			var rLoaded = total ? self.source.getLoaded() / total : 0;

			self._loading.setStyle('width', (100 * rLoaded) + '%');
		},

		/**
		* Updates the progress indicator, showing the current playhead position.
		* @param pos Playhead position (in milliseconds). May differ from position retrieved from sound when seeking.
		*/
		'updatePosition': function (pos) {
			var self = this;

			var duration = self.source ? self.source.getDuration() : 0;
			var rPlayed = pos && duration ? pos / duration : 0;

			self._position.setStyle('width', (100 * rPlayed) + '%');
		},

		/**
		* The position within a sound playback associated with the graphical UI object event.
		* @param event A mouse event with x and y position coordinates.
		*/
		_getPlayheadPosition: function (event) {
			var self = this;
			var coord = self._statusbar.getCoordinates();
			var r = (event.page.x - coord.left) / coord.width;
			if (self.source) {
				var duration = self.source.getDuration();
				if (duration) {
					return Math.floor(r * duration);
				}
			}
			return 0;
		}
	});

	var Timing = new Class({
		'initialize': function () {
			var self = this;
			self._clock = new Element('div', {
				'class': 'metaudio-timing'
			}).adopt(
				self._current = new Element('span', {
					'class': 'metaudio-current'
				}),
				new Element('span', {  // separator
					'html': ' / '
				}),
				self._total = new Element('span', {
					'class': 'metaudio-total'
				})
			);
			self.updateLoaded();
			self.updatePosition();
		},

		'toElement': function () {
			return this._clock;
		},

		/**
		* The recording whose data the clock is showing.
		*/
		'source': false,

		/**
		* Updates the total time of the recording that is playing.
		*/
		'updateLoaded': function () {
			var self = this;
			var duration = self.source ? self.source.getDuration() : 0;
			self._total.set('text', getFormattedTime(duration));
		},

		/**
		* Updates the current time of the recording that is playing.
		* @param pos Playhead position (in milliseconds). May differ from position retrieved from sound when seeking.
		*/
		'updatePosition': function (pos) {
			this._current.set('text', getFormattedTime(pos));
		}
	});

	/**
	* Adds graphical user interface elements to the HTML document.
	*/
	document.addEvent('domready', function () {
		// test spectrogram HTML element support
		settings.spectrogram = settings.spectrogram && isCanvasSupported();

		// initialize Flash
		window.metaudio.init(settings);

		if (typeof(window.metaudio.create) == 'undefined') {  // metaudio not initialized, check Flash version at http://www.adobe.com/software/flash/about/
			return;
		}

		// add control interface for each playable sound object
		var anchors = document.getElements('a.metaudio-player');
		anchors.each(function (anchor) {
			var timing = settings.clock && new Timing;
			var peakdata = settings.peak && new PeakDataVisualizer;
			var spectrogram = settings.spectrogram && new Spectrogram;  // check for spectrogram support
			var control = new Element('div', { 'class': 'metaudio-control' });  // a pseudo-control that acts as a visual aid
			var statusbar = settings.statusbar && new StatusBar;

			// add control interface
			[statusbar,control,spectrogram,peakdata,timing].each(function (item) {
				item && $(item).inject(anchor, 'after');
			});

			// suppress default anchor behavior
			anchor.addEvent('click', function (event) {
				event.preventDefault();  // prevent clicks on sound links taking user away from page
			});

			// retrieve HTML list item that wraps anchor
			var listitem = anchor.getParents('li')[0];

			// prevent clicks on links in description area pausing/resuming sound playback
			$$(listitem.getElements('a').filter(function (anchor) {
				return !anchors.contains(anchor);
			})).addEvent('click', function (event) {
				event.stopPropagation();
			});

			// initialize statusbar and clock to zero
			if (statusbar) {
				statusbar.updateLoaded();
				statusbar.updatePosition();
			}
			if (timing) {
				timing.updateLoaded();
				timing.updatePosition();
			}

			listitem.addEvent('click', function () {
				var url = anchor.href;  // refers to the variable anchor in the outer context

				// stop playing all recordings except the one activated
				window.metaudio.pauseAllBut(url);
				listitem.getSiblings().removeClass('sm2_active');

				var sound = window.metaudio.sounds[url];
				if (!sound) {
					// create sound object and set source for statusbar and clock
					// bind user actions to sound actions and sound events to user interface changes
					sound = window.metaudio.create({
						url: url,
						usePeakData: true,
						useWaveformData: !!spectrogram,
						onload: function () {
							statusbar && statusbar.updateLoaded();
						},
						onplay: function () {
							listitem.addClass('sm2_playing');
						},
						onstop: function () {
							listitem.removeClass('sm2_playing sm2_paused');
						},
						onpause: function () {
							listitem.removeClass('sm2_playing');
							listitem.addClass('sm2_paused');
						},
						onresume: function () {
							listitem.removeClass('sm2_paused');
							listitem.addClass('sm2_playing');
						},
						onfinish: function () {
							listitem.removeClass('sm2_playing').removeClass('sm2_paused');
							statusbar && statusbar.updatePosition();
							if (settings.autoPlayNext) {
								listitem.getNext().addClass('sm2_active').triggerEvent('click');  // automatically start playing next sound recording
							}
						},
						onseek: function (pos) {
							statusbar && statusbar.updatePosition(pos);
							timing && timing.updatePosition(pos);
						},
						whileloading: function () {
							statusbar && statusbar.updateLoaded();
							timing && timing.updateLoaded();
						},
						whileplaying: function () {
							var pos = this.getPosition();

							// update statusbar
							statusbar && statusbar.updatePosition(pos);

							// update clock
							timing && timing.updatePosition(pos);

							// update channel volume peaks
							if (peakdata) {
								this.getPeak && peakdata.update(this.getPeak());
							}

							// update spectrogram
							if (spectrogram) {
								this.getWaveform && spectrogram.update(this.getWaveform());
							}
						}
					});
					statusbar && (statusbar.source = sound);
					timing && (timing.source = sound);
				}

				// pause sound currently playing or resume sound paused previously
				if (listitem.hasClass('sm2_active')) {  // recording selected
					if (listitem.hasClass('sm2_paused')) {  // sound playing or paused
						sound.resume();
					} else if (listitem.hasClass('sm2_playing')) {
						sound.pause();
					} else {  // sound not yet loaded or stopped
						sound.play();
					}
				}
				listitem.addClass('sm2_active');
			});
		});

		if (document.all) {  // Internet Explorer only
			/**
			* Address a Flash unloading bug in IE.
			*/
			function _removeSoundPlayer() {
				var el = document.all['metaudio-player'];
				if (el) {
					el.removeNode(true);
				}
			}

			if (window.addEventListener) {
				window.addEventListener('unload', _removeSoundPlayer, false);
			} else if (window.attachEvent) {
				window.attachEvent('onunload', _removeSoundPlayer);
			} else {
				window.unload = _removeSoundPlayer;
			}
		}
	});
}