
jQuery(document).ready( function () {
	
	var siteURL = jQuery('body').attr('site-url');
	
	jQuery('input.Icheck').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
		increaseArea: '20%' // optional
	});
	
	tinymce.init({selector: '.textarea',
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste jbimages responsivefilemanager"
        ],
        //toolbar1: "insertfile undo redo | styleselect | bold italic | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image jbimages",
        toolbar1: "insertfile undo redo | styleselect | bold italic | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image responsivefilemanager media",
        toolbar2: "link unlink anchor | forecolor backcolor | print preview code | categories_button  | tags_button | articles_button",
        fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",
        relative_urls: false,
		height: "300",
    	width: '99.5%',
    	image_advtab: true,
    	external_filemanager_path: siteURL+"assets/administrator/filemanager/",
	    filemanager_title:"Responsive Filemanager" ,
	    external_plugins: { "filemanager" : siteURL+"assets/administrator/filemanager/plugin.min.js"},
	    setup: function (editor) {
		    editor.addButton('articles_button', {
		      	text: 'Articles',
				icon: false,
				tooltip: 'Article List',
				onclick: function() {
					var selectedVal = editor.selection.getContent({format : 'text'});
					editor.windowManager.open( {
						title: 'Insert Article Link',
						url: siteURL+'articlesModal/'+selectedVal,
                        width: jQuery(window).width() * .75,
                        height: jQuery(window).height() * .75,
						buttons: [{
		                    text: 'Add Article',
		                    onclick: function(e) {
		                    	console.log(e);
		                    	if(tinymce.activeEditor.article_data != '' && tinymce.activeEditor.article_data != undefined) { 
	                                tinymce.activeEditor.execCommand('mceInsertContent', false, tinymce.activeEditor.article_data);
	                                top.tinymce.activeEditor.windowManager.close();
		                    	} else {
		                    		swal("Please select article.");
		                    	}
                            }
		                }, {
		                    text: 'Cancel',
		                    onclick: 'close'
		                }],
						onsubmit: function( e ) {
							editor.insertContent( '[id="' + e.data.listboxName + '"]');
						}
					});
				}
		    }),
		    editor.addButton('categories_button', {
		      	text: 'Categories',
				icon: false,
				tooltip: 'Category List',
				onclick: function() {
					var selectedVal = editor.selection.getContent({format : 'text'});
					editor.windowManager.open( {
						title: 'Insert Category Link',
						url: siteURL+'categoriesModal/'+selectedVal,
                        width: jQuery(window).width() * .75,
                        height: jQuery(window).height() * .75,
						buttons: [{
		                    text: 'Add Category',
		                    onclick: function(e) {
		                    	console.log(e);
		                    	if(tinymce.activeEditor.category_data != '' && tinymce.activeEditor.category_data != undefined) { 
	                                tinymce.activeEditor.execCommand('mceInsertContent', false, tinymce.activeEditor.category_data);
	                                top.tinymce.activeEditor.windowManager.close();
		                    	} else {
		                    		swal("Please select category.");
		                    	}
                            }
		                }, {
		                    text: 'Cancel',
		                    onclick: 'close'
		                }],
						onsubmit: function( e ) {
							editor.insertContent( '[id="' + e.data.listboxName + '"]');
						}
					});
				}
		    }),
		    editor.addButton('tags_button', {
		      	text: 'Tags',
				icon: false,
				tooltip: 'Tag List',
				onclick: function() {
					var selectedVal = editor.selection.getContent({format : 'text'});
					editor.windowManager.open( {
						title: 'Insert Tag Link',
						url: siteURL+'tagsModal/'+selectedVal,
                        width: jQuery(window).width() * .75,
                        height: jQuery(window).height() * .75,
						buttons: [{
		                    text: 'Add Tag',
		                    onclick: function(e) {
		                    	console.log(e);
		                    	if(tinymce.activeEditor.tag_data != '' && tinymce.activeEditor.tag_data != undefined) { 
	                                tinymce.activeEditor.execCommand('mceInsertContent', false, tinymce.activeEditor.tag_data);
	                                top.tinymce.activeEditor.windowManager.close();
		                    	} else {
		                    		swal("Please select tag.");
		                    	}
                            }
		                }, {
		                    text: 'Cancel',
		                    onclick: 'close'
		                }],
						onsubmit: function( e ) {
							editor.insertContent( '[id="' + e.data.listboxName + '"]');
						}
					});
				}
		    });
	 	}
    });
    
    jQuery('.iframe-btn').fancybox({
        'width': 880,
        'height': 570,
        'type': 'iframe',
        'autoScale': false,
    });
    
    jQuery('[data-toggle="popover"]').popover();
    
    jQuery(".colorpicker-group").colorpicker();
    
    jQuery('.datetimepicker-control').datetimepicker({
    	format: 'YYYY-MM-DD HH:mm:ss'
    });
    
    jQuery('.datepicker-control').datetimepicker({
    	format: 'YYYY-MM-DD'
    });
    
    //Date range as a button
    jQuery('.daterange-filter-input-group .daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          jQuery('.daterange-filter-input-group .daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          jQuery('.daterange-filter-input-group input[name="filter[start_date]"]').val(start.format('YYYY-MM-DD'));
          jQuery('.daterange-filter-input-group input[name="filter[end_date]"]').val(end.format('YYYY-MM-DD'));
        }
    );

    
    
    //Initialize Select2 Elements
    jQuery(".select2").select2();
    
    //jQuery UI sortable for the todo list
	jQuery(".todo-list").sortable({
		placeholder: "sort-highlight",
		handle: ".handle",
		forcePlaceholderSize: true,
		zIndex: 999999
	});
	
	jQuery(document).on('click', '.btn-browse', function(){
		var file = jQuery(this).parent().parent().parent().find('.file-input');
		file.trigger('click');
	});
	
	jQuery(document).on('change', '.file-input', function(){
		jQuery(this).parent().find('.form-control').val(jQuery(this).val().replace(/C:\\fakepath\\/i, ''));
	});
    
	
	// CLEAE CACHE AJAX CALL
	jQuery('.clear-cache-ajax').click( function() {
		
		// get the hash 
   		var csrf_test_name = jQuery("input[name=csrf_test_name]").val();
   		
		jQuery.ajax({
			method: "POST",
			url: baseURL+"clearcacheAjax",
			data: { csrf_test_name: csrf_test_name},
			dataType: "json",
			beforeSend: function() {
				swal("Wait clearing cache...", { closeOnClickOutside: false, buttons: false});
			}
		})
		.done(function( response ) {
			
			jQuery("input[name=csrf_test_name]").val(response.csrf_test_name);
			swal.close();
			swal('Cache deleted successfully.', { icon: 'success'});
			
		});
		
	});
	
});
