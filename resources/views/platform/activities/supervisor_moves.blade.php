@extends('sales.layouts.master_2')
@section('css')
    <link id="pagestyle" href="{{asset('museum/css/app.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('museum/css/font.awesome.css')}}" rel="stylesheet"/>
    <link href="{{asset('museum/css/style.css')}}" rel="stylesheet"/>

    <link href="{{asset('museum/css/bootstrap.min.css')}}" rel="stylesheet"/>

@endsection
@section('content')

   @if(auth('admin')->user()->supervisor_type == 'platform')
       <div class="row">
           <div class="col-md-12 col-lg-12">
               <div class="card">
                   <div class="card-header">
                       <h3 class="card-title">Supervisor Status</h3>
                   </div>
                   <div class="card-body">
                       <div class="table-responsive">
                           <!--begin::Table-->
                           <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                               <thead>
                               <tr class="fw-bolder text-muted bg-light">
                                   <th class="min-w-25px">#</th>
                                   <th class="min-w-125px">Supervisor Name</th>
                                   <th class="min-w-125px">Status</th>
                                   <th class="min-w-125px">Time</th>
                               </tr>
                               </thead>
                               <tbody>

                               @foreach($supervisor as $val)

                                   <tr>
                                       <td>{{ $loop->iteration  }}</td>
                                       <td>{{ $val->name }}</td>
                                       <td>{{ $val->status }}</td>
                                       <td>{{ $val->created_at->format('Y-m-d H:i:s') ?? '--' }}</td>
                                   </tr>
                               @endforeach
                               </tbody>
                           </table>
                       </div>
                   </div>
               </div>
           </div>

       </div>
   @endif
@endsection

@section('js')

    <script src="{{asset('museum/js/popper.min.js')}}"></script>
    <script src="{{asset('museum/js/bootstrap.min.js')}}"></script>

    <!-- <script src="js/all.min.js"></script> -->
    <script src="{{asset('museum/js/bootstrap.bundle.min.js')}}"></script>

    <!-- drag and drop -->
    <script src="{{asset('museum/js/main.js')}}"></script>

    <!-- plugins -->
    <script src="{{asset('museum/js/plugins/perfect-scrollbar.min.js')}}"></script>

    <!-- dashboard Js -->
    <script src="{{asset('museum/js/app.min.js')}}"></script>

<script>
    $(document).ready(function () {
            toastr.options.timeOut = 5000;
        @if (Session::has('error'))
        toastr.error('{{ Session::get('error') }}');
        @elseif(Session::has('success'))
        toastr.success('{{ Session::get('success') }}');
        @endif
    });

</script>
{{--    <script>--}}
{{--        // Accept Group--}}
{{--        import {timeout} from "../../../../public/assets/admin/plugins/charts-c3/d3.v5.min";--}}

{{--        $('.btnAccept').on('click', function (e) {--}}
{{--            e.preventDefault();--}}


{{--            var group = $('#group_id').data('id');--}}
{{--            var movementId = $('#movementId').val();--}}
{{--            // var supervisor = $('#supervisor_id').val();--}}
{{--            $.ajax({--}}
{{--                url: "{{ route('groupAccept') }}",--}}
{{--                method: 'POST',--}}
{{--                _token: "{{ csrf_token() }}",--}}
{{--                data: {--}}
{{--                    'group_id': group,--}}
{{--                    'id': movementId,--}}
{{--                    // 'supervisor': supervisor,--}}
{{--                }, success: function () {--}}
{{--                    toastr.success('Accept success');--}}
{{--                    location.reload();--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--        // End Accept Group--}}

{{--        // Not Accept Group--}}
{{--        $('.btnNotAccept').on('click', function (e) {--}}
{{--            e.preventDefault();--}}


{{--            var group = $('#group_id').data('id');--}}
{{--            var movementId = $('#movementId').val();--}}
{{--            $.ajax({--}}
{{--                url: "{{ route('groupNotAccept') }}",--}}
{{--                method: 'POST',--}}
{{--                _token: "{{ csrf_token() }}",--}}
{{--                data: {--}}
{{--                    'group_id': group,--}}
{{--                    'id': movementId,--}}
{{--                    // 'supervisor': supervisor,--}}
{{--                }, success: function () {--}}
{{--                    toastr.success('Not Accept success');--}}
{{--                    location.reload();--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--        // End Not Accept Group--}}
{{--    </script>--}}

@endsection
