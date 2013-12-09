
(function(settings, undefined) {
    
    if(window.wpFeatureBoxEmbed == settings.baseurl) {
        return false;
    }
    
    window.wpFeatureBoxEmbed = settings.baseurl;
    
    if (window.jQuery === undefined || window.jQuery.fn.jquery !== '1.10.2') {
        
        loadScript('//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', function() {
            init();
        });
        
    } else {
        init();
    }
    
    function init() {
        if(!jQuery.isReady) {
            jQuery(document).ready(function() {
                main(jQuery);
            });
        } else {
            main(jQuery);
        }
    }
    
    function loadScripts(callback) {
        
        var i = 0;
        
        var doScript = function() {
            if(settings.scripts[i]) {
                if(window[settings.scripts[i].varName] == undefined) {
                    loadScript(settings.scripts[i].srcUrl, function() {
                        i++;
                        doScript();
                    });
                } else {
                    i++;
                    doScript();
                }
            } else {
                if(typeof callback == 'function') {
                    callback();
                }
            }
            
        };
        
        doScript();
        
    }
    
    function loadScript(src, callback) {
        
        var scriptTag = document.createElement('script');
        scriptTag.setAttribute('type','text/javascript');
        scriptTag.setAttribute('src', src);
        if (scriptTag.readyState) {
            scriptTag.onreadystatechange = function () {
                if ((this.readyState == 'complete' || this.readyState == 'loaded') && typeof callback === 'function') {
                    callback();
                }
            };
        } else if(typeof callback === 'function') {
            scriptTag.onload = callback;
        }
        
        (document.getElementsByTagName('head')[0] || document.documentElement).appendChild(scriptTag);
        
    }
    
    function main($) {
        
        if(!$('#wp-feature-box-css').length) {
            var css = document.createElement('link');
            css.setAttribute('rel', 'stylesheet');
            css.setAttribute('id', 'wp-feature-box-css');
            css.setAttribute('href', settings.css);
            css.setAttribute('type', 'text/css');
            css.setAttribute('media', 'all');
            (document.getElementsByTagName('head')[0] || document.documentElement).appendChild(css);
        }
        
        var nodes = $('.wp-feature-box-embed');
        
        var currentNode = 0;
        
        nodes.each(function() {
            
            var node = $(this);
            
            var ids = node.attr('data-ids');
            
            if(ids) {
                
                if(ids.indexOf(',') !== -1)
                    ids = ids.split(',');
                else
                    ids = [ids];
                
                $.ajax({
                    url: settings.baseurl,
                    cache: true,
                    timeout: 5000,
                    data: {
                        ids: ids,
                        action: settings.action
                    },
                    success: function(embed) {
                        
                        currentNode++;
                        
                        if(embed.error) {
                            console.log(embed.error);
                        } else {
                            var $embed = $(embed.html);
                            node.replaceWith($embed);
                            if(settings.footer)
                                $(settings.footer).insertAfter($embed);
                        }
                        
                        if(currentNode === nodes.length) {
                            loadScripts();
                        }
                        
                    },
                    error: function(xhr, err) {
                        
                        console.log(err);
                        
                    },
                    dataType: 'jsonp'
                });
                
            }
            
        });
    }
    
})(wpFeatureBoxSettings);