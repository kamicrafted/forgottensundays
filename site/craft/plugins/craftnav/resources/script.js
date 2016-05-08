$(document).ready(function() {

    var admin_url = $(location).prop('pathname').split('/')[1];

    // Edit Action
    $('.build_page a.settings, .build_page a.link').on('click', function(e){
        e.preventDefault();
        if ($(this).parents('li ol li').length) {
            $("#form select[name='parent']").val($(this).closest('ol').parent('li').attr('data-id')).prop('selected', true);;
        }
        $("#form input[name='id']").val($(this).parents('.dd-item').attr('data-id'));
        $("#form input[name='text']").val($(this).prev('a').text());
        $("#form input[name='link']").val($(this).prev('a').attr('data'));
    })

    // Delete action
    $('a.delete').on('click', function(){
        if ($('.grid.first').hasClass('build_page')) {
            confirmFormDelete($(this).parents('.dd-item').attr('data-id'), 'b');
        } else {
            confirmFormDelete($(this).next('input[name="id"]').val(), 'a');
        }
    });

    // Create page actions 1 of 3
    $('input#name').keyup(function() {
        var str = $(this).val();
        str = str.toLowerCase().replace(/[^a-zA-Z 0-9 _]+/g, '');
        str = str.replace(/ /g,"_");
        $('input#title').val(str);
        $('.custom_replace em').text(str);
    });
    
    // Create page actions 2 of 3
    $('input#title').keyup(function(){
        var str = $(this).val();
        str = str.toLowerCase().replace(/[^a-zA-Z 0-9 _]+/g, '');
        str = str.replace(/ /g,"_");
        $('input#title').val(str);
        $('.custom_replace em').text(str);
    });

    // Snappy Open
    $('.js-contact').on('click', function(e)
    {
        e.preventDefault();
        SnappyWidget.open({contact: true});
    });

    // Collapse and Expand all
    $('#nestable-menu').on('click', function(e)
    {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });

    // Go update each nav position
    var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target), output = list.data('output');
        if (window.JSON) {
            order_data = list.nestable('serialize');
            $.ajax({
                type: 'POST',
                data: {data : order_data},
                url: '/' + admin_url + '/craftnav/ordernav',
              success: function(data){
                // console.log(data);
                //console.log('success');
              },
              error: function(data){
                // console.log(data);
                //console.log('failure');
              }
            });
        } else {
            //console.log('JSON browser support required for this demo.');
        }
    };

    // Fire up the drag and drop
    $('.dd').nestable({
        maxDepth:3,

    }).on('change', updateOutput);


    // Index page functions
    function confirmFormDelete(event_id, which) 
    {
        var answer = confirm("Are you sure you want to delete this? This can not be undone!");
        // console.log(event_id);
        // console.log(which);
        if (answer) {
            if (which == 'a') {
                removeNav(event_id);
            } else {
                removeNavitem(event_id);
            }
        }
    }

    function removeNav(event_id) 
    {
        $.ajax({
            type: 'POST',
            data: { id: event_id },
            url: '/' + admin_url + '/craftnav/deletenav',
          success: function(data){
            $('input[value="'+event_id+'"]').parents('tr').slideUp();
            // console.log('success');
          },
          error: function(data){
            // console.log('failure');
          }
        });
    }    

    function removeNavitem(event_id) 
    {
        // console.log(event_id);
        $.ajax({
            type: 'POST',
            data: { id: event_id },
            url: '/' + admin_url + '/craftnav/deleteitem',
          success: function(data){
            $('.dd-item[data-id="'+event_id+'"]').slideUp();
            // console.log('success');
          },
          error: function(data){
            // console.log('failure');
          }
        });
    }

});