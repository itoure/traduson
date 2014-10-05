var Lyrics = {

	run : function(){
		
		$('.setThumbUp').on("click", function(event){
			event.preventDefault();
			var disabled = $(this).attr('disabled');
			if(!disabled){
				var objBtn = $(this).next();
				var mark = objBtn.html();
				Lyrics.rateLyrics('up', mark, objBtn);
			}
		});
		
		$('.setThumbDown').on("click", function(event){
			event.preventDefault();
			var disabled = $(this).attr('disabled');
			if(!disabled){
				var objBtn = $(this).next();
				var mark = objBtn.html();
				Lyrics.rateLyrics('down', mark, objBtn);
			}
		});
		
		$('.searchYoutube','#formAddLyrics').on("click", function(event){
			event.preventDefault();
			Lyrics.searchYoutube();
		});
		
		$('.btn-comment','.block-btn-comment').on("submit", function(event){
			event.preventDefault();
			Lyrics.addComment();
		});
		
		$('#artiste','#formAddLyrics').on("keydown", function(event){
			$('#title','#formAddLyrics').prop('disabled', false);
		});
		
		$('#artiste','#formAddLyrics').typeahead({
		    source: function(query, process) {
		        return $.ajax({
		            url: "/lyrics/get-artiste-autocomplete",
		            type: 'post',
		            data: {query: query},
		            dataType: 'json',
		            success: function(json) {
		                return typeof json == 'undefined' ? false : process(json);
		            }
		        });
		    }
		});
		
		$('#title','#formAddLyrics').typeahead({
		    source: function(query, process) {
		        return $.ajax({
		            url: "/lyrics/get-title-autocomplete",
		            type: 'post',
		            data: {
		            	query: query,
		            	artiste: $('#artiste','#formAddLyrics').val()
		            },
		            dataType: 'json',
		            success: function(json) {
		                return typeof json == 'undefined' ? false : process(json);
		            }
		        });
		    }
		});

	},
	
	rateLyrics : function(rate, mark, objBtn){
		
		var idLyrics = $('#idLyrics').val();
		
		var request = $.ajax({
			  url: "/lyrics/rate",
			  type: "POST",
			  data: {
				  rate : rate, 
				  idLyrics : idLyrics,
				  mark : mark
			  },
			  dataType: "json"
			});
			 
			request.done(function(data) {
				objBtn.html(data);
				$('.setThumbUp').attr('disabled', true);
				$('.setThumbDown').attr('disabled', true);
			});
			 
			request.fail(function(jqXHR, textStatus) {
			});
	},
	
	searchYoutube : function(){
		var artiste = $('#artiste','#formAddLyrics').val();
		var title = $('#title','#formAddLyrics').val();
		var url = 'http://www.youtube.com/results?search_query='+encodeURIComponent(artiste)+'+'+encodeURIComponent(title);
		window.open(url);
	},
	
	
	addComment : function(){
		
		var request = $.ajax({
			  url: "/lyrics/add-comment",
			  type: "POST",
			  data: {idLyrics : idLyrics},
			  dataType: "json"
			});
			 
			request.done(function(data) {
			});
			 
			request.fail(function(jqXHR, textStatus) {
			});
		
	}
	
		
};

$(document).ready(function() {
	Lyrics.run();
});
