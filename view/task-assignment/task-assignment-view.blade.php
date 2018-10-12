<div class="modal-header bg-info">
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-capitalize text-white"><i class="fa fa-file fa-lg"></i> {{ $page_title }} Details</h4>
</div>

<div class="modal-body view">

	<div class="row">
	<div class="col-md-12">

		<div class="row">

			<div class="col-md-6">
				<label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="Task Assignment Type" title="Task Assignment Type">Task </label>
				<div class="col-xs-12 col-md-12 input-group">
					@foreach($students as $student)
						@if(isset($row->StudentID) && $row->StudentID == $student->id)
							<div class="form-control strip-tags">{{ $student->StudentName }}</div>
						@endif
					@endforeach
				</div>
			</div>

			<div class="col-md-6">
				<label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="Assigned To" title="Assigned To">Assigned to </label>
				<div class="col-xs-12 col-md-12 input-group">
					@foreach($users as $user)
						@if(isset($row->AssignedTo) && $row->AssignedTo == $user->id)
							<div class="form-control strip-tags">{{ $user->FirstName.$user->LastName }}</div>
						@endif
					@endforeach
				</div>
			</div>

			<div class="col-md-6">
				<label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="StudentID" title="Student">Student </label>
				<div class="col-xs-12 col-md-12 input-group">
					@foreach($students as $student)
						@if(isset($row->StudentID) && $row->StudentID == $student->id)
							<div class="form-control strip-tags">{{ $student->StudentName }}</div>
						@endif
					@endforeach
				</div>
			</div>

			<div class="col-md-6">
				<label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="AssignTaskName">Assign Task Name <span class="required" aria-required="true"></span></label>
				<div class="col-xs-12 col-md-12 input-group">
					<div class="form-control strip-tags">{{ $row->AssignTaskName }}</div>
				</div>
			</div>

			<div class="col-md-6">
				<label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="AssignTaskName">Start Date <span class="required" aria-required="true"></span></label>
				<div class="col-xs-12 col-md-12 input-group">
					<div class="form-control strip-tags">{{ $row->ITPCalculationStartDate }}</div>
				</div>
			</div>

			<div class="col-md-6">
				<label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="AssignTaskName">Ending Date <span class="required" aria-required="true"></span></label>
				<div class="col-xs-12 col-md-12 input-group">
					<div class="form-control strip-tags">{{ $row->ITPCalculationStartDate }}</div>
				</div>
			</div>

			<div class="col-md-12">
				<label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="AssignTaskName">Instruction <span class="required" aria-required="true"></span></label>
				<div class="col-xs-12 col-md-12 input-group">
					<div class="form-control strip-tags">{{ $row->TaskInstruction }}</div>
				</div>
			</div>

			<div class="col-md-12" id="studentWiseTask">
				<label class="control-label col-xs-6 col-md-12 text-left p-left-0">Task Activities: </label>
				@if(isset($stdMtpGroups))
					@foreach($stdMtpGroups as $stdMtpGrousNew)
						@foreach($stdMtpGrousNew as $key => $stdMtpGroup)
							@php
								$domain = $stdMtpGroup[0]['DomainID'];
                                $sub_domain = $stdMtpGroup[0]['SubDomainID'];
                                $area = $stdMtpGroup[0]['DomainAreaID'];
                                $Activity = $stdMtpGroup[0]['ActivityID'];

                                $DSArAc = DB::table('curriculum')
                                    ->select('CurriName')
                                    ->whereIn('id', [$domain, $sub_domain, $area, $Activity])
                                    ->get();
							@endphp
							<div class="input-group col-lg-12 col-sm-12">
								<div class="panel panel-info">
									<div class="panel-heading task_assignment" data-perform="panel-collapse">
										<div class="pull-left">
											<span class='task_span'>{{ $DSArAc[0]->CurriName }} > {{ $DSArAc[1]->CurriName }} > {{ $DSArAc[2]->CurriName }} > {{ $DSArAc[3]->CurriName }}</span>
											<a href="#" ><i class="ti-minus task_assignment"></i></a> <a href="#" data-perform="panel-dismiss"></a>
										</div>
									</div>
									<div class="panel-wrapper collapse" aria-expanded="true">
										<div class="panel-body task_assignment">
											<ul class="list-icons">
												@foreach($stdMtpGroup as $key2 => $val2)
													@php
														$tasks = \App\Models\TaskActivity::where('id',$val2['TaskID'])->pluck('ActivityName','id')->toArray();
                                                        $isCheck = 0;
													@endphp
													<li class="task_assignment">
														<div class="checkbox checkbox-danger">
															@foreach($checkTasks as $assignListid => $checkTask)
																@if($checkTask == key($tasks))
																	<input name='tasks[]' onclick='checkSelectedDiv(this)' class='isSelected' id='{{ $key.$key2 }}' type='checkbox' value='{{ key($tasks) }}' checked>
																	<label class='task_assignment' for='{{ $key.$key2 }}'> <span class='task_assignment'>{{ $tasks[key($tasks)] }}</span> </label>
																	@php $isCheck = 1 @endphp
																	@break
																@else
																	@continue
																@endif
															@endforeach
															@if($isCheck==0)
																<input name='tasks[]' id='{{ $key.$key2 }}' type='checkbox' value='{{ key($tasks) }}'>
																<label class='task_assignment' for='{{ $key.$key2 }}'> <span class='task_assignment'>{{ $tasks[key($tasks)] }}</span> </label>
															@endif
														</div>
													</li>
												@endforeach
											</ul>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					@endforeach

				@endif
			</div>

			<div class="col-md-6">
				<label class="control-label col-xs-6 col-md-12 text-left p-left-0">Updated By: </label>
				<div class="col-xs-12 col-md-12" id="TaskActivity">
					@if(isset($row->updatedUser))
						{{ $row->updatedUser->FirstName .' '. $row->updatedUser->LastName }}
						at  {{ $row->updated_at}}
					@else
						There is no update record.
					@endif
				</div>
			</div>

			<div class="col-md-6">
				<label class="control-label col-xs-6 col-md-12 text-left p-left-0">Created By: </label>
				<div class="col-xs-12 col-md-12" id="TaskActivity">
					@if(isset($row->createdUser))
						{{ $row->createdUser->FirstName .' '. $row->createdUser->LastName }}
						at  {{ $row->created_at}}
					@else
						There is no create record.
					@endif
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
<div class="modal-footer text-right">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
<script>
    $(document).ready(function () {
        $('.isSelected:not(:checked)').each(function () {

            $(this).parents().eq(5).children().first().removeClass('bg-red');
        });
        $('.isSelected:checked').each(function () {

            $(this).parents().eq(5).children().first().addClass('bg-red');

        });
    })
</script>