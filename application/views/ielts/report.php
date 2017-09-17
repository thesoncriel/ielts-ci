<div class="container test-report">
	<div class="panel">
		<h2 class="panel-heading">Candidate Details</h2>
		<div class="panel-body row">
			<strong class="col-xs-2">Test Date</strong>
			<span class="col-xs-2"><?=$testdate?></span>
		</div>
	</div>
	<div class="panel">
		<h2 class="panel-heading">Test Results</h2>
		<table class="table table-hover table-bordered table-band-score">
			<colgroup>
				<col class="header-width"/>
			</colgroup>
			<thead>
				<tr class="active">
					<th></th>
					<th>Listening</th>
					<th>Reading</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>Score</th>
					<td><?=$l_score?></td>
					<td><?=$r_score?></td>
				</tr>
				<tr>
					<th>Band</th>
					<td>Band <?=$l_band?></td>
					<td>Band <?=$r_band?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="panel">
		<h2 class="panel-heading">점수 등급 설명</h2>
		
		<table class="table table-striped table-font-mod">
				<colgroup>
					<col style="width: 90px"/>
					<col/>
					<col/>
				</colgroup>
				<thead>
					<tr>
						<th>성적</th>
						<th>수준</th>
						<th>설명</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Band 9</td>
						<td>Expert</td>
						<td>영어를 완전히 이해하고, 적절하면서도 정확하고 유창하게 구사할 수 있는 실력을 갖추었습니다.</td>
					</tr>
					<tr>
						<td>Band 8</td>
						<td>Very Good</td>
						<td>간혹 부정확하고 부적절한 어휘나 표현을 쓰고 익숙하지 못한 상황에서는 질문을 잘못 이해하는 경우가 있지만, 대체적으로 언어구사능력이 뛰어나고 복잡하고 전문적인 토론을 잘 이끌어 갈 수 있습니다.</td>
					</tr>
					<tr>
						<td>Band 7</td>
						<td>Good</td>
						<td>부정확하거나 부적절한 언어를 사용하거나 일부상황에서 오해를 하는 경우가 있지만, 그래도 언어구사능력이 있다고 여겨질 경우 받게 되는 점수입니다. 일반적으로 난이도가 높은 언어를 잘 구사하며 자세한 내용에 대한 이해가 가능한 영어 실력입니다.</td>
					</tr>
					<tr>
						<td>Band 6</td>
						<td>Competent</td>
						<td>부정확하고 부적절한 표현을 사용하며 익숙하지 못한 상황에서는 영어를 잘못 이해하는 경우가 있지만, 친숙한 상황에서는 영어를 상당히 잘 이해하고 구사할 수 있는 정도의 실력을 갖췄을 때 받게 되는 점수입니다.</td>
					</tr>
					<tr>
						<td>Band 5</td>
						<td>Modest</td>
						<td>내용을 오해하거나 이해하는 데 실수가 많을 수 있으며 유창함이 부족해 때로는 의사소통이 되지 않는 경우가 있기는 하지만, 전반적인 내용 이해가 가능하고 부분적인 언어 구사력을 지니고 있는 경우입니다. 자신이 원하는 내용에 대해서 기본적인 의사소통은 가능한 수준입니다.</td>
					</tr>
					<tr>
						<td>Band 4</td>
						<td>Limited</td>
						<td>익숙한 몇 가지 상황에서만 언어구사가 가능하고, 내용의 이해나 표현에 있어서 오해가 잦으며 복잡하고 어려운 어휘를 사용하는 데 서투른 수준입니다.</td>
					</tr>
					<tr>
						<td>Band 3</td>
						<td>Extremely Limited</td>
						<td>아주 익숙하고 단순한 상황에서 의미의 전달 혹은 이해만 가능한 경우이며, 의사소통이 중단되는 경우가 생깁니다.</td>
					</tr>
					<tr>
						<td>Band 2</td>
						<td>Intermittent</td>
						<td>단어만을 사용해 가장 기본적인 의사만을 전달하는 것을 제외하고는 의사소통이 힘든 경우입니다.</td>
					</tr>
					<tr>
						<td>Band 1</td>
						<td>Non</td>
						<td>사실상 언어 구사력이 없다고 판단되는 상태입니다.</td>
					</tr>
				</tbody>
			</table>
	

		<div class="panel-body">
			<?/*// 그래프 부분
			<div class="row">
				<div class="col-xs-6">
					<div id="chart_report1" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>
				</div>
				<div class="col-xs-6">
					<div id="chart_report2" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>
				</div>
				<h4 class="col-xs-5">SPAC</h4>
				<strong class="col-xs-2">
					Administrator's Signature
				</strong>
				<div>
					comment
				</div>
			</div>*/?>
		</div>
	</div>
	<div class="panel panel-default hidden-print">		
		<div class="panel-body text-center">
			<a href="#" class="btn btn-lg btn-primary" data-rule="print">PRINT</a>
		</div>
	</div>
</div>