/*
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
jQuery(window).load(function() {
    var galleryImgs = jQuery('.videos-items-grid-thumb');
    if (galleryImgs.length > 0) {
        galleryImgs.each(function(index) {
            var parent = this.getParent(".videos-grid-item, .videos-list-item, .playlists-list-item");
            var container = parent.getSize().y/2;
            var margin = (container - (this.height/2));
            this.setStyle('margin-top', margin + 'px');
        });
    }
});

