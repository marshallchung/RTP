<div id="divLoadingMask" 
	style="width:100%; height:60%; margin:auto; position:fixed; bottom:0%; left:0; z-index:99; display:none">
    <div class="modal-header text-center" style="background-color:#FFFFBB">
        <h1>Processing...</h1>
    </div>
</div>

<script type="text/javascript">
	function execWithLoading (exec) {
		$('#divLoadingMask').show(100, function() {
			exec(function() {
				$('#divLoadingMask').hide();
			});
		});
	}
</script>