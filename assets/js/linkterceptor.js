/**
 * Simple External links
 */
 
jQuery(document).ready(function() {
	//var hostname = new RegExp(location.host);
	        jQuery('a').not('[href^="http"],[href^="https"],[href^="mailto:"],[href^="#"]').each(function() {
//Use .attr() to modify the href, when you provide a callback function
//the arguments passed are the attribute index and its value
            jQuery(this).attr('href', function(index, value) {
//This fix solves the problem when you aren't at the root level of a site
// e.g. if you are at site.com/page1/ and the link href is "do/something"
// we need to make sure the absolute url becomes newsite.com/page1/do/something
// if we just prepended the new domain we would actually get newsite.comdo/something
// which obviously wouldn't work
                if (value != undefined){
					if (value.substr(0,1) !== "/") {
						value = window.location.pathname + value;
					}
	//When you return from the callback function for .attr() it will set the attribute
	//to this new value.
	//We don't use a trailing slash on mynewurl.com because it will already exist if
	//the href starts with a / or it will be part of window.location.pathname
					
					var hostBase = location.host;
					//var clippedHost = hostToClip.substring(0, hostToClip.length - 1);
					
					return 'http://' + hostBase + value;
				}
        });
    });
});

jQuery(document).ready(function() {
	    var hostname = new RegExp(location.host);
        // Act on each link
        jQuery('a').each(function(){
			var internal = jQuery(this).text();
			if (internal.length != 0) {
				var found = jQuery(this).find('img');
				if (found.length == 0) {
					// Store current link's url
					var url = jQuery(this).attr("href");

					if (url != undefined){
						// Test if current host (domain) is in it
						if(hostname.test(url)){
						   // If it's local...
						   //jQuery(this).addClass('local');
						}
						else if(url.slice(0, 1) == "#"){
							// It's an anchor link
							//jQuery(this).addClass('anchor'); 
						}
						else {
						   // a link that does not contain the current host
						   jQuery(this).addClass('linktercepted');  
						}
					}
				} else {
					// Store current link's url
					var url = jQuery(this).attr("href");

					if (url != undefined){
						// Test if current host (domain) is in it
						if(hostname.test(url)){
						   // If it's local...
						   //jQuery(this).addClass('local');
						}
						else if(url.slice(0, 1) == "#"){
							// It's an anchor link
							jQuery(this).addClass('anchor'); 
						}
						else {
						   // a link that does not contain the current host
							jQuery(this).addClass('linktercepted');
							}); 
						}
					}			
				}
			}
});

jQuery(document).ready(function() {

	jQuery('.linktercepted').click(function (evt) {
		evt.preventDefault();
		var element = jQuery(this);
		var url = element.attr("href");
		var link_title = element.attr("title");
		jQuery.post(ajaxurl, {
			action: 'linktercept',
			link_title: link_title,
			url: url
		},
		function (response) {
			
		});
		
	});

});