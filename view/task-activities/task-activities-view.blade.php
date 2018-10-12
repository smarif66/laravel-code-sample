<div class="modal-header bg-info">
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-capitalize text-white"><i class="fa fa-file fa-lg"></i> {{ $page_title }} Details</h4>
</div>

<div class="modal-body">

	<div class="row">
		<div class=" col-xs-12 col-lg-12 col-md-12 ">
		@php
			$filter_route = str_replace('/{id}', '', Route::current()->uri);
            $route = url( str_replace('-form', '', $filter_route). (isset($row[0]->id)? "-update/".$row[0]->id : '-store') );
		@endphp

		<!-- // Start From -->
			<!-- // Start From -->
			{!! Form::open(array(
                'url'   => $route,
                'files' => true,
                'name'  => 'Request::segment(2)',
                'id'    => 'data_form',
                'class' => "form-horizontal task_activities_form curriForm form_design col-xs-12 col-lg-7 col-lg-offset-2 col-md-10 col-md-offset-1 ds-form-".Request::segment(2)
                )) !!}
			<span id="curriSpan">

                <div class="form-group col-md-6">
                    <label class="control-label col-xs-12 col-md-3" for="Task Id" title="Task Id">Activity </label>
                    <div class="col-xs-12 col-md-9 input-group">
                        @if(isset($tasks) && $tasks != null)
                            @foreach($tasks as $task)
                                @if($task->id == $row->TaskID)
                                    <div class="form-control strip-tags">{{ $task->CurriName }}</div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label class="control-label col-xs-12 col-md-3" for="domainWeightFactorID" title="domainWeightFactorID">Domain Weight </label>
                    <div class="col-xs-12 col-md-9 input-group">
                        @foreach($weights as $key=>$weight)
                            @if(isset($row->domainWeightFactorID) && $row->domainWeightFactorID == $key)
                                <div class="form-control strip-tags">{{ $weight }}</div>
                            @endif
                        @endforeach
                    </div>
                </div>
				@if(isset($rows) && $rows != null)

					@foreach($rows as $value)
						<div class="form-group col-md-8">
                            <label class="control-label col-xs-12 col-md-4" for="id">Task Name<span class="required" aria-required="true"></span></label>
                            <div class="col-xs-12 col-md-8 input-group">
                                <div class="form-control strip-tags">{{ $value->ActivityName }}</div>
                            </div>
                        </div>
						<div class="form-group col-md-4">
                        <label class="control-label col-xs-12 col-md-3" for="Task Id" title="Task Id">Type </label>
                        <div class="col-xs-12 col-md-8 input-group">
                            @if(isset($value->type) && $value->type=='P') <div class="form-control strip-tags"> Pre-Vocational</div> @endif
                            @if(isset($value->type) && $value->type=='V') <div class="form-control strip-tags"> Vocational </div> @endif
                            @if(isset($value->type) && $value->type=='I') <div class="form-control strip-tags">Independent </div> @endif
                        </div>
                    </div>
						{{--<input type="hidden" name="ActivityName['id']" value="{{ $value->id }}" required>--}}
					@endforeach
				@endif
            </span>

		{!! Form::close() !!}
		<!-- // ..End From -->
		</div>
	</div>
</div>
<div class="modal-footer text-right">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
