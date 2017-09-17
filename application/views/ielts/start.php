<div class="container">
	<div class="jumbotron">
	<? if ($subject === "L"): ?>
		<div class="ieltsstart_title">IELTS<br/>
		 <small>International English Language Testing System</small></div>
		<div class="lead text-center">
			<? if ($status_current != "E"): ?>
			Now the test will begin.<br/>
			Click "Start" to begin the test.
			<? else: ?>
			<div class="start-text-margin"></div>
			<? endif; ?>
		</div>
		<? if ($status_current != "E"): ?>
		<div class="lead text-center text-primary">
			Listening Test가 시작되니<br/>스피커 음량을 조절해 주세요.
		</div>
		<? endif; ?>
		<div class="row">
			<? if ($status_current == "E"): ?>
			<form action="/ielts/start" method="post" class="col-xs-offset-1 col-xs-4 text-center form-group-height-fix">
				<input type="hidden" name="do_this" value="restart"/>
				<div class="form-group">
					<label class="text-warning">시험이 끝났습니다. 재시험을 원하시면 아래 버튼을 누르세요.</label>
					<button class="btn btn-lg btn-info">Restart <span class="glyphicon glyphicon-circle-arrow-right"></span></button> 
				</div>
			</form>
			<form action="/ielts/report" method="post" class="col-xs-offset-2 col-xs-4 text-center form-group-height-fix">
				<label class="text-primary">방금 치른 시험의 결과를 다시 보시려면 아래 버튼을 누르세요.</label>
				<?/*<div class="form-group">
					<input type="text" name="sessionid" value="<?=$sessionid?>" class="form-control"/>
				</div>*/?>
				<input type="hidden" name="data" value="1"/>
				<button class="btn btn-lg btn-primary">Report <span class="glyphicon glyphicon-circle-arrow-right"></span></button>
			</form>
			<? else: ?>
			<form action="/ielts/listening" method="post" class="col-xs-offset-4 col-xs-4 text-center">
				<div class="form-group">
					<?/*<input type="text" name="sessionid" value="<?=$sessionid?>" class="form-control"/>
					<? if ($invalid == true): ?>
					<div class="alert alert-danger">
						<strong>ID 입력이 잘 못 되었습니다.</strong><br/>
						잘 모르실 경우 공란으로 비워두십시요.
					</div>
					<input type="hidden" name="valid_msg" value="1"/>
					<? endif; ?>
					*/?>
				</div>
				<input type="hidden" name="data" value="1"/>
				<button class="btn btn-lg btn-primary">Start <span class="glyphicon glyphicon-circle-arrow-right"></span></button>
			</form>
			<? endif; ?>
		</div>
		
	<? elseif ($subject === "R"): ?>
		<? if ($l_refresh == true): ?>
		<div class="alert alert-warning text-center">
			Listening 시험을 이미 한번 수행 했습니다.<br/>
			다시 수행할 수 없으므로 종료하고 다음 시험을 진행 합니다.
		</div>
		<? endif; ?>
		<div class="lead text-center">
			Now you have completed LISTENING test.<br/>
			Click "Next" to start <strong class="text-primary">READING test.</strong>
		</div>
		<form action="/ielts/reading" method="post" class="text-center">
			<input type="hidden" name="data" value="1"/>
			<button class="btn btn-lg btn-primary">Start <span class="glyphicon glyphicon-circle-arrow-right"></span></button>
		</form>
	<? endif; ?>
	</div>
</div>