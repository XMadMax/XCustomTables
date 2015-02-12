<?php
echo ' 
		<style>
		    .input-medium-custom { min-height: 32px; height: 100%; width: 400px !important;}
		    .input-select-custom { min-height: 30px; height: 100%; width: 280px !important;}
		    .input-large-custom { min-height: 100px; height: 100%; width: 400px !important;}
		    .select-combodate-custom { min-height: 28px; height: 100% !important;}
		    .editable-container {position:relative !important; }
		    .editable-popup {position:relative; height: 100px !important;}
		    .editable-container.editable-inline {position: absolute !important;}
		    .editable-click a {text-decoration: none !important; }
		    .editableform .control-group { 
			padding: 6px; 
			background-color: #f3f3f3; 
			border: 0.5px solid #ccc;
			box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
			transition: border 0.2s linear 0s, box-shadow 0.2s linear 0s;
		    }
		    .widefat thead th.check-column {width: 24px;}
		    #xctmodal {   
			border: solid thick silver;
			-webkit-border-radius: 8px;
			-moz-border-radius: 8px;
			border-radius: 8px;
			}
		</style>
		<link href="'.plugins_url().'/xcustomtables/xctcore/css/bootstrapcustom.css?v='.XCT_CSS_VERSION.'" rel="stylesheet">
		<script src="'.plugins_url().'/xcustomtables/xctcore/js/bootstrap.min.js?v='.XCT_JS_VERSION.'"></script>
		<link href="'.plugins_url().'/xcustomtables/xctcore/css/bootstrap-editable.css?v='.XCT_CSS_VERSION.'" rel="stylesheet">
		<script src="'.plugins_url().'/xcustomtables/xctcore/js/bootstrap-editable.min.js?v='.XCT_JS_VERSION.'"></script>
		<script src="'.plugins_url().'/xcustomtables/xctcore/js/moment-with-locales.min.js?v='.XCT_JS_VERSION.'"></script>
		<script src="'.plugins_url().'/xcustomtables/xctcore/js/combodate.js?v='.XCT_JS_VERSION.'"></script>
		<script type="text/javascript">
			jQuery(document).ready(function($){
';
		    
