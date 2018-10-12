@extends('layouts.admin')

@section('stylesheets')

@endsection

@section('content')

<!-- .row -->
<div class="row">
	<div class="col-sm-12">
	    <div class="white-box">
	        
            @include('includes.page-section-title')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

	        <p class="text-muted m-b-30"> </p>
	        <div class="table-responsive">
	            <table id="myTable" class="table table-striped sd-datatable">
	                <thead>
	                    <tr>
                            <th class="hidden">ID</th>
                            <th>Activity</th>
	                        <th>Task</th>
                            <th class="text-center">Log</th>
	                        <th class="hidden">action</th>
	                    </tr>
	                </thead>
	                <tbody>
                    @if(count($rows) > 0)
                        @php($isTaskID=0)
                        @foreach($rows as $row)
                            @if($row->TaskID == $isTaskID)
                                @continue
                            @else
                                <tr class="{{ ( $row->IsActive=='N' )? 'danger' : '' }}">
                                    <td class="hidden">{{ $row->id }} </td>
                                    <td>{{ $row->task->CurriName }}</td>
                                    <td>
                                        <a class="" title="Edit" onclick="getDetails(this)"
                                           href="javascript:void(0)"
                                           data-href="{{ route(str_replace('/', '.', Route::current()->uri) .'.edit', $row->TaskID) }}">

                                            {{ $row->ActivityName }}
                                        </a>
                                    </td>
                                    <td class="text-center text-samll">
                                        <small>

                                            Create: {{ $row->createdUser->FirstName .' '. $row->createdUser->LastName }}
                                            @ {{ $row->created_at }}
                                            <br>
                                            @if(isset($row->updatedUser))
                                                Update: {{ $row->updatedUser->FirstName .' '. $row->updatedUser->LastName }}
                                                @ {{ $row->updated_at }}
                                            @else
                                                There is no update record.
                                            @endif

                                        </small>

                                    </td>

                                    <!-- action menu -->
                                    <td class="ds-actions-rows text-right hidden">

                                        <!-- Edit -->
                                        <a class="btn btn-dark btn-circle" title="Edit" onclick="getDetails(this)"
                                           href="javascript:void(0)"
                                           data-href="{{ route(str_replace('/', '.', Route::current()->uri) .'.edit', $row->id) }}">

                                            <i class="fa fa-pencil  fa-lg"></i>
                                        </a>

                                    </td>
                                </tr>
                                @php($isTaskID = $row->TaskID)
                            @endif
                        @endforeach
                    @endif
                    </tbody>
	            </table>

                <!-- Pagination -->
                {{--{{ $rows->links() }}--}}

	        </div>
            <!-- /.table-responsive -->
	    </div>
	</div>

</div>
<!-- /.row -->

@endsection


@section('jscripts')

@endsection