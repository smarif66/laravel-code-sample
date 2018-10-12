<div class="modal-header bg-info">
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-capitalize text-white"><i class="fa fa-file fa-lg"></i> {{ $page_title }} Details</h4>
</div>

<div class="modal-body view">

	<div class="row">
	<div class="col-md-12">

		<div class="row">
			<div class="col-md-6">
				<label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="Task Assignment Type" title="Task Assignment Type">Task Assignment</label>
				<div class="col-xs-12 col-md-12 input-group">
					<div class="form-control strip-tags">{{ $row->task_assignment->AssignTaskName }}</div>
				</div>
			</div>

			<div class="col-md-6">
				<label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="Assigned To" title="Assigned To">Student</label>
				<div class="col-xs-12 col-md-12 input-group">
					<div class="form-control strip-tags">{{ $row->student->StudentName }}</div>
				</div>
			</div>

			<div class="col-md-6">
				<label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="StudentID" title="Student">Teacher </label>
				<div class="col-xs-12 col-md-12 input-group">
					<div class="form-control strip-tags">{{ $row->teacher->FirstName.' '.$row->teacher->LastName }}</div>
				</div>
			</div>

			<div class="col-md-6">
				<label class="control-label col-xs-12 col-md-12 text-left p-left-0" for="AssignTaskName"> Assesment Date</label>
				<div class="col-xs-12 col-md-12 input-group">
					<div class="form-control strip-tags">{{ $row->AssesmentDate }}</div>
				</div>
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
		<div class="row p-20 posting_transaction">
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="white-box posting_transaction">
						<p class="text-muted">(1) Full physical prompt (2) Partial Support (3) Pectoral guidance / follow others (4) Verbal Instruction (5) Independent <span id="error_span"></span></p>
						<table id="mainTable" class="table editable-table table-bordered table-striped m-b-0">
							<thead>
							<tr>
								<th>SL.</th>
								<th>Task</th>
								<th>Time</th>
								<th>Sequence</th>
								<th>Quality</th>
								<th>Quantity</th>
								<th>Delivery</th>
								<th>Time Tak</th>
								<th>Target</th>
								<th>Ans Score</th>
								<th>Z Local</th>
								<th>Z Global</th>
								<th>Remark</th>
							</tr>
							</thead>
							<tbody class="tbodyy" id="taskAssignmentListData">
							@foreach($TaskAssesments as $key=>$taskAssesment)
								<tr>
									<td>{{ $key+1 }}</td>
									<td>{{ $taskAssesment->task_activities->ActivityName }}</td>
									<td>{{ $taskAssesment->tskTime }}</td>
									<td>{{ $taskAssesment->tskSequence }}</td>
									<td>{{ $taskAssesment->tskQuality }}</td>
									<td>{{ $taskAssesment->tskQuantity }}</td>
									<td>{{ $taskAssesment->tskDelivery }}</td>
									<td>{{ $taskAssesment->tskTimetaken }}</td>
									<td>{{ $taskAssesment->tskTarget }}</td>
									<td>{{ $taskAssesment->AnsScore }}</td>
									<td>{{ $taskAssesment->LocalZScore }}</td>
									<td>{{ $taskAssesment->GlobalZscore }}</td>
									<td class="remarks">{{ $taskAssesment->Remarks }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
<div class="modal-footer text-right">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
