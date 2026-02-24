<script>
	function modalAlert (title, msg, optVars) {
		if (typeof(optVars) === 'undefined') {
			optVars = {};
		}
		var div = $('#errorModal');
		div.find('[term="title"]').html(title);
		div.find('[term="msg"]').html(msg);
		div.find('[term="return"]').html('關閉').off();
		div.modal();

		if ('btnReturn' in optVars) {
			div.find('[term="return"]').html(optVars.btnReturn);
		}
		if ('callback' in optVars) {
			optVars.callback(div);
		}
	}
	function modalConfirm (title, msg, optVars) {
		if (typeof(optVars) === 'undefined') {
			optVars = {};
		}
		var div = $('#confirmModal');
		div.find('[term="title"]').html(title);
		div.find('[term="msg"]').html(msg);
		div.find('[term="submit"]').html('確認送出').off();
		div.modal();

		if ('btnReturn' in optVars) {
			div.find('[term="return"]').html(optVars.btnReturn);
		}
		if ('btnSubmit' in optVars) {
			div.find('[term="submit"]').html(optVars.btnReturn);
		}
		if ('callback' in optVars) {
			optVars.callback(div);
		}
	}
	function modalproduct (title, msg, optVars) {
		if (typeof(optVars) === 'undefined') {
			optVars = {};
		}
		var div = $('#productModal');
		div.find('[term="title"]').html(title);
		div.find('[term="msg"]').html(msg);
		div.find('[term="return"]').html('關閉').off();
		div.modal();

		if ('btnReturn' in optVars) {
			div.find('[term="return"]').html(optVars.btnReturn);
		}
		if ('callback' in optVars) {
			optVars.callback(div);
		}
	}
	$(function() {
		$.fn.extend({
			myAjaxForm: function (success, beforeSubmit) {
				if (typeof(beforeSubmit) === 'undefined') {
					beforeSubmit = function(arr, $form, options){};
				}
				$(this).ajaxForm({
					dataType: 'json',
					error: function() {
						modalAlert('伺服器錯誤', '目前系統忙碌中，如果重新整理頁面後仍不能使用，請連絡系統維護人員，將儘快為您處理，抱歉。'	);
					},
					success: function (data, statusText, xhr, $form) {
						if (data.error) {
							var callback = {};
							if (data.redirect) {
								callback = function(div) {
									div.on('click', '[term="return"]', function() {
										window.location = data.redirect;
									});
								};
							}
							modalAlert('操作失敗', data.error, {
								callback: callback
							});
							return;
						}
						success(data, $form);
					},
					beforeSubmit: function(arr, $form, options) {
						beforeSubmit(arr, $form, options);
					}
				});
			},
		});
	});
</script>
<div id="errorModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button term="close" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" style="margin-top:10px;font-size:20px; font-weight:bold; text-align:center">
					<img src="" alt="" style="margin-bottom:7px; height: 30px">
					<span term="title"> </span>
				</h4>
			</div>
			<div class="modal-body">
				<label term="msg" style="font-size:18px; font-weight:normal;">

				</label>
			</div>
			<div class="modal-footer">
				<button term="return" type="button"
					class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400"
					data-dismiss="modal">關閉</button>
			</div>
		</div>
	</div>
</div>

<div id="confirmModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 term="title" class="modal-title"
					style="margin-top:10px;font-size:20px; font-weight:bold; text-align:center">
					<img src="" alt="" style="margin-bottom:7px; height: 30px">

				</h4>
			</div>
			<div class="modal-body">
				<label term="msg" style="font-size:18px; font-weight:normal;">

				</label>
			</div>
			<div class="modal-footer">
				<button term="return" type="button"
					class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400"
					data-dismiss="modal">返回修改</button>
				<button term="submit" type="button"
					class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400"
					data-dismiss="modal">確認送出</button>
			</div>
		</div>
	</div>
</div>