<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die;

$user = JFactory::getUser();

$uploadUrl = JUri::root(true) . '/index.php?option=com_mijovideos&view=upload&task=upload&format=raw&user_id=' . $user->id;
$swfFile = JUri::root(true) . '/components/com_mijovideos/assets/swf/Swiff.Uploader.swf';
//$editTask = ($app->isAdmin() ? 'editmedia' : 'mediaform');

$javascript = <<<EOD
//<![CDATA[

/**
 * FancyUpload Showcase
 *
 * @license		MIT License
 * @author		Harald Kirschner <mail [at] digitarald [dot] de>
 * @copyright	Authors
 */

window.addEvent('domready', function() { // wait for the content

	// our uploader instance

	var up = new FancyUpload2($('mijovideos_status'), $('mijovideos_list'), { // options object
		// we console.log infos, remove that in production!!
		verbose: false,

		url: '$uploadUrl',

		// path to the SWF file
		path: '$swfFile',

        data: '',

        timeLimit: 9999,

		// this is our browse button, *target* is overlayed with the Flash movie
		target: 'mijovideos_browse',

		// graceful degradation, onLoad is only called if all went well with Flash
		onLoad: function() {
			$('mijovideos_status').removeClass('hide'); // we show the actual UI
			$('mijovideos_fallback').destroy(); // ... and hide the plain form

			// We relay the interactions with the overlayed flash to the link
			this.target.addEvents({
				click: function() {
					return false;
				},
				mouseenter: function() {
					this.addClass('hover');
				},
				mouseleave: function() {
					this.removeClass('hover');
					this.blur();
				},
				mousedown: function() {
					this.focus();
				}
			});

			// Interactions for the 2 other buttons

			$('mijovideos_clear').addEvent('click', function() {
				up.remove(); // remove all files
				return false;
			});

			$('mijovideos_upload').addEvent('click', function() {
				up.start(); // start upload
				return false;
			});
		},

		// Edit the following lines, it is your custom event handling
onBeforeStart: function() {
    var listSize = this.fileList.length;
    for (var i=0; i < listSize; i++){
        //alert(JSON.encode($('adminForm').toQueryString().parseQueryString()));

        // Set a flag to avoid leading & in query string.
        var flag = false;
        var adminFormData = '';
/**
 * We originally put the category selection in a select box, but now just a hidden input
 *
        if ($('adminForm').jform_catid) {
            for (var j=0; j<$('jform_catid').options.length; j++) {
                if ($('jform_catid').options[j].selected) {
                    if ($('jform_catid').options[j].value > 0)
                    {
                        if (flag)
                        {
                            adminFormData+= '&catid=' + $('jform_catid').options[j].value;

                        }
                        else
                        {
                            var flag = true;
                            adminFormData+= 'catid=' + $('jform_catid').options[j].value;
                        }
                    }
                }
            }
        }
 */
        if ($('adminForm').jform_catid && $('adminForm').jform_catid.value > 0) {
            if (flag) {
                adminFormData+= '&catid=' + $('adminForm').jform_catid.value;
            } else {
                var flag = true;
                adminFormData+= 'catid=' + $('adminForm').jform_catid.value;
            }
        }
        if ($('adminForm').jform_album_id && $('adminForm').jform_album_id.value > 0) {
            if (flag) {
                adminFormData+= '&album_id=' + $('adminForm').jform_album_id.value;
            } else {
                var flag = true;
                adminFormData+= 'album_id=' + $('adminForm').jform_album_id.value;
            }
        }
        if ($('adminForm').jform_playlist_id && $('adminForm').jform_playlist_id.value > 0) {
            if (flag) {
                adminFormData+= '&playlist_id=' + $('adminForm').jform_playlist_id.value;
            } else {
                var flag = true;
                adminFormData+= 'playlist_id=' + $('adminForm').jform_playlist_id.value;
            }
        }
        if ($('adminForm').jform_group_id && $('adminForm').jform_group_id.value > 0) {
            if (flag) {
                adminFormData+= '&group_id=' + $('adminForm').jform_group_id.value;
            } else {
                var flag = true;
                adminFormData+= 'group_id=' + $('adminForm').jform_group_id.value;
            }
        }
        if ($('adminForm').jform_user_id && $('adminForm').jform_user_id.value > 0) {
            if (flag) {
                adminFormData+= '&user_id=' + $('adminForm').jform_user_id.value;
            } else {
                var flag = true;
                adminFormData+= 'user_id=' + $('adminForm').jform_user_id.value;
            }
        }
        if (adminFormData) {
            this.fileList[i].setOptions({data: adminFormData.parseQueryString()});
        }
    }
},

                /**
		 * Is called when files were not added, "files" is an array of invalid File classes.
		 *
		 * This example creates a list of error elements directly in the file list, which
		 * hide on click.
		 */
		onSelectFail: function(files) {
			files.each(function(file) {
				new Element('li', {
				    'class': 'validation-error',
					html: file.validationErrorMessage || file.validationError,
					title: MooTools.lang.get('FancyUpload', 'removeTitle'),
					events: {
						click: function() {
							this.destroy();
						}
					}
				}).inject(this.list, 'top');
			}, this);
		},

		/**
		 * This one was directly in FancyUpload2 before, the event makes it
		 * easier for you, to add your own response handling (you probably want
		 * to send something else than JSON or different items).
                 *
                 * In this URL we create a dummy space to prevent Joomla converting to SEF
		 */
		onFileSuccess: function(file, response) {
			var json = new Hash(JSON.decode(response, true) || {});

			if (json.get('status') == '1') {
				file.element.addClass('file-success');
				file.info.set('html', '<strong>Succesfully uploaded</strong> <a href="index'+'.php?option=com_mijovideos&view=videos&task=edit&cid[]='+json.get('id')+'" target="_top">Edit</a>');
			} else {
				file.element.addClass('file-failed');
				file.info.set('html', '<strong>An error occured:</strong> ' + (json.get('error') ? (json.get('error') + ' #' + json.get('code')) : response));
			}
		},

		/**
		 * onFail is called when the Flash movie got bashed by some browser plugin
		 * like Adblock or Flashblock.
		 */
		onFail: function(error) {
			switch (error) {
				case 'hidden': // works after enabling the movie and clicking refresh
					alert('To enable the embedded uploader, unblock it in your browser and refresh (see Adblock).');
					break;
				case 'blocked': // This no *full* fail, it works after the user clicks the button
					alert('To enable the embedded uploader, enable the blocked Flash movie (see Flashblock).');
					break;
				case 'empty': // Oh oh, wrong path
					alert('A required file was not found, please be patient and we fix this.');
					break;
				case 'flash': // no flash 9+ :(
					alert('To enable the embedded uploader, install the latest Adobe Flash plugin.')
			}
		}

	});

});
//]]>
EOD;

$this->document->addScriptDeclaration($javascript);