@extends('layouts.admin')

@section('stylesheets')
	<link href="{{asset('plugins/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
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
	            <table id="myTable" class="table table-striped">
	                <thead>
	                    <tr>
                            <th class="hidden">ID</th>
	                        <th>TaskAssignementID</th>
	                        <th>StudentID</th>
                            <th>TeacherID</th>
                            <th>AssesmentDate</th>
                            <th class="text-center">Log</th>
	                        <th class="hidden">action</th>
	                    </tr>
	                </thead>
	                <tbody>
                    @if(count($rows) > 0)
                        @foreach($rows as $row)
                        <tr class="{{ ( $row->IsActive=='N' )? 'danger' : '' }}">
                            <td class="hidden">{{ $row->id }} </td>
                            <td>
                                <a class="" title="Edit" onclick="getDetails(this)"
                                   href="javascript:void(0)"
                                   data-href="{{ route(str_replace('/', '.', Route::current()->uri) .'.edit', $row->id) }}">

                                    {{ $row->TaskAssignementID }}
                                </a>
                            </td>
                            <td>{{ $row->StudentID }}</td>
                            <td>{{ $row->TeacherID }}</td>
                            <td>{{ $row->AssesmentDate }}</td>
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
                                <!-- View -->
                                <a class="btn btn-dark btn-circle" title="View" onclick="getDetails(this)"
                                   href="javascript:void(0)"
                                   data-toggle="modal" data-target="#dynamicAddModal"
                                   data-href="{{ route(str_replace('/', '.', Route::current()->uri) .'.view', $row->id) }}">

                                    <i class="fa fa-eye fa-lg"></i>
                                </a>

                                <!-- status/isActive -->
                                <a class="btn btn-dark btn-circle"
                                   title="Status: {{ ($row->IsActive=='y')? 'Active' : 'Inactive' }}"
                                   href="javascript:isStatus('{{ Request::segment(1) }}-status', {{$row->id}}, '{{$row->IsActive}}');">

                                    <i class="fa fa-check  fa-lg {{ ($row->IsActive == 'Y')? 'text-success' : 'text-danger' }}"></i>
                                </a>

                                <!-- Edit -->
                                {{--<a class="btn btn-dark btn-circle" title="Edit" onclick="getDetails(this)"--}}
                                   {{--href="javascript:void(0)"--}}
                                   {{--data-href="{{ route(str_replace('/', '.', Route::current()->uri) .'.edit', $row->id) }}">--}}

                                    {{--<i class="fa fa-pencil  fa-lg"></i>--}}
                                {{--</a>--}}

                                <!-- Delete -->
                                @if(Auth::user()->UserType == 'S')
                                    <a class="row-{{ $row->id }} btn btn-dark btn-circle" title="Delete"
                                       href="javascript:deleteData('{{ Request::segment(1) }}-delete', {{ $row->id }});">
                                        <i class="fa fa-trash  fa-lg"></i>
                                    </a>
                                @else
                                    <a class="row-{{ $row->id }} btn btn-dark btn-circle" title="Delete"
                                       href="javascript:alert('You can\'t delete default template.')">

                                        <i class="fa fa-trash fa-lg text-muted"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    </tbody>
	            </table>

                <!-- Pagination -->
                {{ $rows->links() }}

	        </div>
            <!-- /.table-responsive -->
	    </div>
	</div>

</div>
<!-- /.row -->

@endsection


@section('jscriptsl')

	<link href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
	<!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
    });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    </script>

@endsection