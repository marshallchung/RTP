<script type="text/javascript">
	$(function() {
		$.fn.extend({
			modalClose: function () {
				$(this).modal('hide');
				$('.modal-backdrop').remove();
			},
			resetForm: function (optVars) {
				if (typeof optVars === 'undefined') {optVars = {};}
				var inputs = $(this).find('input');
				inputs.filter('input[name!="_token"][name!="numPerPage"][type!="checkbox"]').val('');
				inputs.filter('[type="checkbox"]').prop('checked', false);
				$(this).find('select').val('');
				for (var attr in optVars) {
					for (var selector in optVars[attr]) {
						var boolean = optVars[attr][selector];
						$(this).find(selector).prop(attr, boolean);
					}
				}
			},
			// myAjaxForm 在common.modalErrorMsg
			myAjaxForm_s: function (success, beforeSubmit) {
				if (typeof beforeSubmit === 'undefined') {beforeSubmit = function(arr, $form, options){};}
				$(this).ajaxForm({
					dataType: 'json',
					error: function () {
						alert('伺服器錯誤，如重整頁面仍然不能使用，請連絡系統人員。');
						return;
					},
					success: function (data, statusText, xhr, $form) {
						if (data.error) {
							alert(data.error);
							return;
						}
						success(data, $form);
					},
					beforeSubmit: function(arr, $form, options) {
						beforeSubmit(arr, $form, options);
					},
					timeout: 0,
				});
			},
			// 設定頁碼 - 將暫存的formData放回form後送出
			setPageMarks: function (opt) {
				opt.divMarks.html('');
				var pages = Math.ceil(opt.count / opt.numPerPage);
				if (pages < 2) { return; }
				
				var divs = [];
				var div = $('<div class="pageBox" style="display:none"></div>').attr('index', 1);
				var divIndex = 1;
				var rows = 0;
				for (var i = 1; i <= pages; i++) {
					rows++;
					div.append('<a class="btn btn-link pageBtn" page="'+i+'">'+i+'</a>');
					
					if (rows === 10 || i === pages) {
						divs.push(div);
						divIndex++;
						var div = $('<div class="pageBox" style="display:none"></div>').attr('index', divIndex);
						rows = 0;
					}
				}
				
				var rows = 0;
				var size = divs.length;
				if (size > 1) {
					for (var i=0; i < size; i++) {
						rows++;
						switch (rows) {
							case 1:
								var target = parseInt($(divs[i]).attr('index')) + 1;
								divs[i].append('<a class="jumper btn btn-link" target="'+target+'">>></a>');
								break;
							case size:
								var target = parseInt($(divs[i]).attr('index')) - 1;
								divs[i].prepend('<a class="jumper btn btn-link" target="'+target+'"><<</a>');
								break;
							default:
								var target = parseInt($(divs[i]).attr('index')) - 1;
								divs[i].prepend('<a class="jumper btn btn-link" target="'+target+'"><<</a>');
								var target = parseInt($(divs[i]).attr('index')) + 1;
								divs[i].append('<a class="jumper btn btn-link" target="'+target+'">>></a>');
						}
					}
				}
				
				for (var i=0; i < size; i++) {
					opt.divMarks.append(divs[i]);
				}
				opt.divMarks.find('[index="1"]').show();
				
				var form = $(this);
				opt.divMarks.off().on('click', '.pageBtn', function() {
					$.each(opt.formData, function(idx, el) {
						form.find('[name="'+el.name+'"]').val(el.value);
					});
					form.find('[name="page"]').val($(this).text());
					form.submit();
				});
				opt.divMarks.on('click', '.jumper', function() {
					var divsHtml = opt.divMarks.find('.pageBox');
					divsHtml.hide();
					var toShow = $(this).attr('target');
					divsHtml.filter('[index="'+toShow+'"]').show();
				});

			},
		});
	});
	function myPost (url, data, success, error) {
		if (typeof error === 'undefined') {
			error = function(msg){ 
				console.log(msg); 
			};
		}
		$.ajax({
			url: url,
			data: data,
			method: 'post',
			dataType: 'json',
			async: false,
			error: function() {
				error('資料取得失敗');
			}, 
			success: function (response) {
				if (response.error) {
					error(response.error);
					return;
				}
				success(response);
			}
		});
	}
	function isEmpty(str) {
		return (!str || 0 === str.length);
	}
	function chunk(array, chunk, job, callback) {
		if (typeof callback === 'undefined') {
			callback = function() {};
		}
		var idx = 0;
		var len = array.length;
		function doChunk() {
	        var cnt = chunk;
	        while (cnt-- && idx < len) {
	            job(array[idx]);
	            ++idx;
	        }
	        if (idx < len) {
	            setTimeout(doChunk, 10);
	        // callback when chunk done
	        } else {
	        	callback();
	        }
	    }    
	    doChunk(); 
	}
</script>