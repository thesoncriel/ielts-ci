<div class="container test-section">
	<? if ($status_current != "E"): ?>
	<form id="form_ajaxAnswer" action="listening/answer" method="post" class="hidden">
		<input type="hidden" name="num" value=""/>
		<input type="hidden" name="val" value=""/>
	</form>
	<video id="video_listening" class="hidden" 
		data-filepath="/media/" 
		data-filepattern="<?=$moduletype.'L'.$module?>#.mp4" 
		data-startdelay="5" 
		data-nextdelay="120">
		<?/*<source src="http://cdn2.carrotenglish.com/ielts/li/<?=$moduletype.'L'.$module?>.mp4" type="video/mp4"></source>*/?>
		<?//<source src="/media/GL11.mp4" type="video/mp4"></source>?>
		<source src="/media/<?=$moduletype.'L'.$module?>1.mp4" type="video/mp4"></source>
	</video>
	<? endif; ?>
	
	
	<form action="start" method="post" class="panel panel-default">
		<input type="hidden" name="timercurr" value="<?=$timercurr?>"/>
		<input type="hidden" name="timerlimit" value="<?=$timerlimit?>"/>
		<input type="hidden" name="type" value="L"/>
		<input type="hidden" name="do_this" value="end"/>
		<input type="hidden" name="status_current" value="<?=$status_current?>"/>
		<div class="panel-heading">
			<h2 class="row">
				<div class="col-xs-offset-2 col-xs-8 text-center">Listening </div>
				<strong class="col-xs-2 text-right lead"><?//.lead는 글자 크기를 키우기 위해 사용 한 것.?>
					<span class="glyphicon glyphicon-time"></span> 
					<span class="text-warning">
						<span id="timer_test"
							data-rule="timer"
							data-timeouttext=""
							data-curr="<?=$timercurr?>" 
							data-limit="<?=$timerlimit?>"
							data-autostart="true">
						00:00
						</span>
					</span>
				</strong>
			</h2>
		</div>
		<div class="panel-body row">
			<div class="col-xs-8">
				<div class="panel">
					<div class="panel-body vertical-scroll">
						<h3 class="hide">LISTENING</h3>
						<img src="/qjpg/<?=$moduletype.'L'.$module?>1.jpg" class="img-responsive" alt="Listening Questions"/>
						<img src="/qjpg/<?=$moduletype.'L'.$module?>2.jpg" class="img-responsive" alt="Listening Questions"/>
						<img src="/qjpg/<?=$moduletype.'L'.$module?>3.jpg" class="img-responsive" alt="Listening Questions"/>
						<img src="/qjpg/<?=$moduletype.'L'.$module?>4.jpg" class="img-responsive" alt="Listening Questions"/>
					</div>
				</div>
			</div>
			<div class="col-xs-4">
				<div class="panel panel-info">
					<div class="panel-heading text-center">
						<h3 class="panel-title">Answer Sheet</h3>
					</div>
					<div id="panel_answerSheet" data-rule="answersheet" data-form="#form_ajaxAnswer" class="panel-body answer-sheet vertical-scroll">
						<? if ($status_current == "E"): ?>
						<div class="alert alert-info" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
							<strong>시험이 모두 종료 되었습니다.</strong>
							풀어 본 문제들을 다시 확인 해 볼 수 있습니다.
						</div>
						<? else: ?>
						<div class="alert alert-info" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
							<strong>주의</strong><br/>
							Listening 시험 도중에 창을 닫거나 새로고침을 하게 되면,<br/>
							시험을 다시 치를 수 없으니 주의 바랍니다. 
						</div>
						<? endif; ?>
						<? if ($timeout == false || $status_current == "E"): ?>
						<ol class="list-group">
							<? foreach($answer_sheet as $index=>$item): ?>
							<li class="list-group-item">
								<div class="row">
									<strong class="col-xs-2"><span class="btn btn-item-seq"><?=$index + 1?>.</span></strong>
									<div class="col-xs-10">
										<? if($item["Q_TYPE"] == "W"): ?>
										<input type="text" name="answer<?=$index?>" class="form-control" id="text_answer<?=$index?>" placeholder="Answer please ..." value="<?=$answer_list[$index]?>"/>
										<? else: ?>
										<div class="btn-group fix-width-alpha" data-toggle="buttons">
											<? foreach($item["A_LABEL"] as $idx => $seqWord): ?>
											<? if($answer_list[$index] == ($idx + 1)): ?>
											<label class="btn btn-default active">
												<input type="radio" name="answer<?=$index?>" value="<?=$idx + 1?>" id="radio_answer<?=$index . $idx?>" autocomplete="off" checked="checked">
												<?=$seqWord?>
											</label>
											<? else: ?>
											<label class="btn btn-default">
												<input type="radio" name="answer<?=$index?>" value="<?=$idx + 1?>" id="radio_answer<?=$index . $idx?>" autocomplete="off">
												<?=$seqWord?>
											</label>
											<? endif; ?>
											<? endforeach; ?>
										</div>
										<? endif; ?>										
									</div>
								</div>
							</li>
							<? endforeach; ?>
						</ol>
						<? endif; ?>
						<div class="alert alert-danger<? if ($timeout == false || $status_current == "E"): ?> hidden<?endif;?>" role="alert">
							<strong>Test is over.</strong>
							Please prepare for the next test.
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-body text-center">
			<input type="hidden" name="subject" value="R"/>
			<button class="btn btn-lg btn-primary">Next <span class="glyphicon glyphicon-circle-arrow-right"></span></button>
		</div>
	</form>
</div>