<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="white-box posting_transaction">
            <div class="table-responsive">
                <table id="mainTable" class="table editable-table table-bordered table-striped m-b-0 table-responsive">
                    <thead>
                    <tr>
                        <th>SL.</th>
                        <th>Task</th>
                        <th>Time</th>
                        <th>Sequence</th>
                        <th>Quantity</th>
                        <th>Quality</th>
                        <th>Delivery</th>
                        <th>Time Taken</th>
                        <th>Target</th>
                    </tr>
                    </thead>
                    <tbody class="tbodyy" id="taskAssignmentListData">
                    @if(isset($taskActivities) && $taskActivities != null)
                        @foreach($taskActivities as $key => $activity)
                            <tr class="post_trans_details_tr">
                                <td>{{ $key+1 }}</td>
                                <td>{{ $activity->ActivityName }}</td>
                                <input type="hidden" name="id[{{$activity->id}}]" value="{{ $activity->id }}">
                                <td><input type="number" min="0" max="5" name="tskTime[{{ $activity->id }}]" value="{{ $activity->tskTime }}"></td>
                                <td><input type="number" min="0" max="5" name="tskSequence[{{ $activity->id }}]" value="{{ $activity->tskSequence }}"></td>
                                <td><input type="number" min="0" max="5" name="tskQuantity[{{$activity->id  }}]" value="{{ $activity->tskQuantity }}"></td>
                                <td><input type="number" min="0" max="5" name="tskQuality[{{ $activity->id }}]" value="{{ $activity->tskQuality }}"></td>
                                <td><input type="number" min="0" max="5" name="tskDelivery[{{ $activity->id }}]" value="{{ $activity->tskDelivery }}"></td>
                                <td><input type="number" min="0" max="5" name="tskTimetaken[{{ $activity->id }}]" value="{{ $activity->tskTimetaken }}"></td>
                                <td><input type="number" min="0" max="5" name="tskTarget[{{ $activity->id }}]" value="{{ $activity->tskTarget }}"></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>