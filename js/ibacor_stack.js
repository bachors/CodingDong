/*********************************************************************
* #### jQuery-Stackoverflow-Search ####
* Coded by Ican Bachors 2016.
* http://ibacor.com/labs/jquery-stackoverflow-search
* Updates will be posted to this site.
*********************************************************************/

$('#stack_title').keydown(function() {
    setTimeout(function() {
        var d = $('#stack_title').val(),
            e = $('.stack_sort').val();
        if (d == '') {
            $('#stack_output').css("display", "none")
        } else {
            ibacor_stack(e, d)
        }
    }, 50)
});

$('.stack_sort').change(function() {
    var d = $('#stack_title').val(),
        e = $(this).val();
    ibacor_stack(e, d);
    return false
});	
	   
$('#add').click(function(){				 
	var inp = $('#cdn');				 
	var i = $('input').size() + 1;				 
	$('<div id="cdn' + i +'"><input type="text" class="cdn-input" name="cdn_url[]" placeholder="css or js"/><span title="remove" id="remove">x</span> </div>').appendTo(inp);				 
	i++;				 
});	

$('body').on('click','#remove',function(){				 
	$(this).parent('div').remove();			   
});				 

function ibacor_stack(e, d) {
    $.ajax({
        url: 'https://api.stackexchange.com/2.2/search?order=desc&sort=' + e + '&site=stackoverflow&intitle=' + d,
        crossDomain: true,
        dataType: 'json'
    }).done(function(b) {
        var c = '';
        $.each(b.items, function(i, a) {
            c += '<p><a href="' + b.items[i].link + '" target="_BLANK">' + b.items[i].title + '</a></p>'
        });
        if (c == '') {
            $('#stack_output').css("display", "none")
        } else {
            $('#stack_output').css("display", "block")
        }
        $('#stack_output').html(c)
    })
}
