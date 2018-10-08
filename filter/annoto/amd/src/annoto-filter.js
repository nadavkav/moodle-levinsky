// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * javscript for component 'filter_annoto'.
 *
 * @package    filter_annoto
 * @copyright  Annoto Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function($) {

    return {
        init: function(playerfound, params) {
            this.params = params;
            if (!params) {
                this.logWarn('empty params');
                return;
            }

            if (!playerfound) {
                this.log('player not found by filter, looking at frontend')
                return this.findPlayer();
            }

            this.bootstrap();
        },
        findPlayer: function() {
            var h5p = $('iframe.h5p-iframe').first().get(0);
            if (!h5p) {
                return;
            }
            if (!h5p.id || h5p.id === '') {
                h5p.id = this.params.defaultPlayerId;
            }
            this.params.playerId = h5p.id;
            this.params.playerType = 'h5p';
            this.bootstrap();
        },
        bootstrap: function() {
            if (this.bootsrapDone) {
                return;
            }
            this.bootsrapDone = true;
            return require([this.params.bootstrapUrl], this.bootWidget.bind(this));
        },
        bootWidget: function() {
            var params = this.params;
            var that = this;
            var nonOverlayTimelinePlayers = ['youtube', 'vimeo'];
            var innerAlignPlayers = ['h5p'];
            var horizontalAlign = 'element_edge';
            if (!params.widgetOverlay || params.widgetOverlay === 'auto') {
                horizontalAlign = (innerAlignPlayers.indexOf(params.playerType) !== -1) ? 'inner' : 'element_edge'
            } else if (params.widgetOverlay === 'inner') {
                horizontalAlign = 'inner';
            }
            var config = {
                clientId: params.clientId,
                position: params.position,
                features: {
                    tabs: params.featureTab,
                    cta: params.featureCTA,
                },
                width: {
                    max: (horizontalAlign === 'inner') ? 320 : 360,
                },
                align: {
                    vertical: params.alignVertical,
                    horizontal: horizontalAlign,
                },
                ux :{
                    ssoAuthRequestHandle: function() {
                        window.location.replace(params.loginUrl)
                    },
                    /* logoutRequestHandle: function() {
                        window.location.replace(params.logoutUrl)
                    } */
                },
                zIndex: params.zIndex ? params.zIndex : 100,
                widgets: [
                    {
                        player: {
                            type: params.playerType,
                            element: params.playerId,
                            mediaDetails : function () {
                                return {
                                    title : params.mediaTitle,
                                    description: params.mediaDescription,
                                    group: {
                                        id: params.mediaGroupId,
                                        type: 'playlist',
                                        title: params.mediaGroupTitle,
                                        privateThread: params.privateThread,
                                    }
                                };
                            },
                        },
                        timeline: {
                            overlayVideo: (nonOverlayTimelinePlayers.indexOf(params.playerType) === -1),
                        },
                    }
                ],
                demoMode: params.demoMode,
                rtl: params.rtl,
                locale: params.locale,
            };

            if (window.Annoto) {
				window.Annoto.on('ready', function (api) {
				var jwt = params.userToken;
				if (api && jwt && jwt !== '') {
					api.auth(jwt).catch(function() {
						that.logError('Annoto SSO auth error');
					});
				}
			});
			if (params.playerType === 'videojs' && window.requirejs) {
				window.require(['media_videojs/video-lazy'], function(vjs) {
					config.widgets[0].player.params = {
						videojs: vjs
					};
					window.Annoto.boot(config);
				});
			} else {
				window.Annoto.boot(config);
			}
			} else {
				that.logWarn('Annoto not loaded');
			}
        },
        log: function(msg, arg) {
            console && console.debug('AnnotoFilterPlugin: ' + msg, arg || '');
        },
        logWarn: function(msg, arg) {
            console && console.warn('AnnotoFilterPlugin: ' + msg, arg || '');
        },
        logError: function(msg, err) {
            console && console.error('AnnotoFilterPlugin: ' + msg, err || '');
        }
    };
});