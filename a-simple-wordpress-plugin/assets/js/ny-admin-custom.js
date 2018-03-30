  jQuery( function() {
	var cache = {};

    jQuery( "#tags" ).autocomplete({
      minLength: charCount,
      source: function( request, response ) {
        var term = request.term;
        if ( term in cache ) {
          response( cache[ term ] );
          return;
        }
		jQuery.post(
			ajaxurl, 
			{
				'action': 'ny_get_country_list',
				'term': request.term
			}, 
			function(data){
				if(data){
					cache[ term ] = data;
					response( data );
				}
				
			}
		);
      }
    });
  });
  
  
