@extends('sales.layouts.master_2')
@section('css')
    <link id="pagestyle" href="{{asset('museum/css/app.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('museum/css/font.awesome.css')}}" rel="stylesheet"/>
    <link href="{{asset('museum/css/style.css')}}" rel="stylesheet"/>

    <link href="{{asset('museum/css/bootstrap.min.css')}}" rel="stylesheet"/>
@endsection
@section('content')

    {{-- assets/uploads/activities  --}}
    <!-- content -->
    <content class="container-fluid pt-4">
        <h2 class="MainTiltle mb-5 ms-4">Egyptian Museum</h2>

        <div class="row mt-5">
            <div class="col-md-6 col-12">

                {{--start div box--}}
                <div class="box"
                >
                    <h3 class="title-box">Waiting Room</h3>
                    <div class="d-flex justify-content-between">
                        {{--                        <button class="btn-report mb-2" type="submit" data-bs-toggle="modal" data-bs-target="#exampleModalReport">--}}
                        {{--                            Report--}}
                        {{--                        </button>--}}
                        {{--                        <button class="btn-report btn-end mb-2" type="submit">End Tour</button>--}}
                    </div>
                    <!-- <div class="item p-3" draggable="true" data-bs-toggle="modal" data-bs-target="#exampleModalAll"> -->

                    @foreach($group_customers_waiting as $group_customer)
                        <div style="background-color: {{ $group_customer->color ?? '' }}"
                             class="items item d-flex justify-content-between" draggable="true" data-bs-toggle="modal"
                             data-bs-target="#showModalDetails-{{ $group_customer->group->id }}">
                            {{ $group_customer->group->title}}
                            <span class="me-2">{{$group_customer->group->group_quantity}}</span>
                        </div>

                        <!-- popup choose showModalDetails -->
                        <div class="modal modalChoose"
                             id="showModalDetails-{{ $group_customer->group->id }}"
                             data-id="{{$group_customer->group->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content modalContentChoose modal-All">
                                    <div class="d-flex justify-content-end m-3">
                                        <button type="button" class="btn-close btn-close-choose"
                                                data-bs-dismiss="modal"
                                                aria-label="Close" id="closeChoose"></button>
                                    </div>
                                    <div class="modal-body d-flex justify-content-between">
                                        <button class="btn-group mb-2" type="submit" data-bs-toggle="modal"
                                                data-bs-target="#exampleModalReport-{{ $group_customer->group->id }}">
                                            Group Details
                                        </button>
                                        <button class="btn-report mb-2" type="submit" data-bs-toggle="modal"
                                                data-bs-target="#moveGroup-{{ $group_customer->group->id }}">
                                            Move group
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- popup choose tourguide -->
                        <div class="modal modalChoose"
                             id="moveGroup-{{ $group_customer->group->id }}"
                             data-id="{{$group_customer->group->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content modalContentChoose modal-All">
                                    <div class="d-flex justify-content-between p-4">
                                        <h6 class="modal-title text-danger" id="exampleModalLabel">Recommended Activity
                                            :</h5>
                                            <button type="button" class="btn-close btn-close-choose"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('groupMoveCreate') }}" method="post">
                                            @csrf
                                            <input type="text" name="group_id" value="{{ $group_customer->group->id }}"
                                                   hidden>

                                            <div class="activity mb-lg-3">
                                                <h6 class="title-choose mb-2">Select color</h6>
                                                <input style="width:200px;right: 66px;top: 16px;position: absolute;"
                                                       type="color" name="color">
                                            </div>

                                            <input type="text" name="supervisor_old"
                                                   value="{{ $group_customer->supervisor_accept_id }}" hidden>

                                            <div class="activity mt-4">
                                                <h6 class="title-choose mb-3">Select Activity</h6>
                                                <div class="form-check">
                                                    <select style="padding: 5px;" name="activity_id"
                                                            class="selectform form-select activitySelect"
                                                            id="activitySelect">
                                                        @foreach($activities as $activity)
                                                            <option
                                                                value="{{ $activity->id }}">{{ $activity->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="activity mt-3">
                                                <h6 class="title-choose mb-3">Select Tourguide</h6>
                                                <div class="form-check">
                                                    <select style="padding: 5px;" name="supervisor_accept_id"
                                                            class="form-select selectform tourGuideSelect"
                                                            id="tourGuideSelect">

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="button mt-3 d-flex justify-content-center">
                                                <button class="btn-accept mb-2" type="submit">
                                                    Move group
                                                </button>
                                            </div>
                                        </form>
                                        <!-- <div class="d-flex justify-content-end">
                                          <button class="btn-select mb-2 mt-3" type="submit">Done</button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- popup table -->
                        <div class="modal modalChoose bd-example-modal-lg"
                             id="exampleModalReport-{{ $group_customer->group->id }}"
                             aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div style="width:1260px;right: 180px;" class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Group Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body table-responsive">
                                        <table class="table border">
                                            <thead>
                                            <tr>
                                                <th scope="col" class="color">ID</th>
                                                <th scope="col" class="color">Name</th>
                                                <th scope="col" class="color">Count</th>
                                                <th scope="col" class="color">Finished Activities</th>
                                                <th scope="col" class="color">Current Activity</th>
                                                <th scope="col" class="color">Time left (mins)</th>
                                                <th scope="col" class="color">Next Activity</th>
                                                <th scope="col" class="color">cashier</th>
                                                <th scope="col" class="color">Actions</th>
                                            </tr>
                                            </thead>
                                            @foreach($group_customer->group->group_customer as $ticket)
                                                <tbody>
                                                <tr>
                                                    <td>{{ $ticket->ticket_id }}</td>
                                                    <td>{{ $ticket->ticket->client->name }}</td>
                                                    <td>{{ $ticket->quantity }}</td>
                                                    <td> ---</td>
                                                    <td>Waiting Room</td>
                                                    <td>waiting</td>
                                                    <td>{{ $ticket->nextActivity->activity->title ?? '' }}</td>
                                                    <td>{{ $ticket->ticket->cashier->name }}</td>
                                                    <td>
                                                        <button class="btn btn-success" data-bs-toggle="modal"
                                                                data-bs-target="#joinGroup-{{ $group_customer->group->id }}">
                                                            join Group
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal modalChoose bd-example-modal-lg"
                             id="joinGroup-{{ $group_customer->group->id }}" aria-labelledby=""
                             aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div style="width:1260px;right: 180px;" class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Groups</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body table-responsive">
                                        <table class="table border">
                                            <thead>
                                            <tr>
                                                <th scope="col" class="color">ID</th>
                                                <th scope="col" class="color">Name</th>
                                                <th scope="col" class="color">Count</th>
                                                <th scope="col" class="color">Actions</th>
                                            </tr>
                                            </thead>
                                            @foreach($group_customer->group->group_customer as $ticket)
                                                <tbody>
                                                <tr>
                                                    <td>{{ $ticket->ticket_id }}</td>
                                                    <td>{{ $ticket->ticket->client_id }}</td>
                                                    <td>{{ $ticket->quantity }}</td>
                                                    <td>
                                                        <button class="joinGroup btn btn-success">
                                                            join
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach

                    <!-- </div> -->
                </div>
                {{--end div box--}}
            </div>


            {{--------------------------------------------------------------------------------------------------------- start activity-----------------------}}
            @foreach($activities as $activity)
                <div class="col-md-6 col-12">


                    {{--start div box--}}
                    <div class="box"
                         style="  background-image: linear-gradient(rgba(246, 238, 207, 0.7), rgba(246, 238, 207,0.9)), url({{URL::to($activity->photo)}}) !important;">
                        <h3 class="title-box">{{$activity->title}}</h3>
                        <div class="d-flex justify-content-between">
                            {{--                        <button class="btn-report mb-2" type="submit" data-bs-toggle="modal" data-bs-target="#exampleModalReport">--}}
                            {{--                            Report--}}
                            {{--                        </button>--}}
                            {{--                        <button class="btn-report btn-end mb-2" type="submit">End Tour</button>--}}
                        </div>
                        <!-- <div class="item p-3" draggable="true" data-bs-toggle="modal" data-bs-target="#exampleModalAll"> -->


                        @foreach($activity->groups as $group)
                            <div style="background-color: {{ $group->group_movement->group_color->color ?? ''}}"
                                 class="items item d-flex justify-content-between divGroup" draggable="true"
                                 data-bs-toggle="modal"
                                 data-bs-target="#showgroupDetails-{{ $group->id }}"
                                 data-id="{{ $group->id }}">
                                {{ $group->title }}
                                <span>{{ ($group->group_movement->accept == 'waiting') ? 'Pending' : 'Active' }}</span>
                                {{--                                <button type="button" ">Group-{{$group->group->id}}</button>--}}
                                <span class="me-2">{{ $group->group_quantity ?? ''}}</span>
                            </div>


                            <!-- popup choose showModalDetails -->
                            <div class="modal"
                                 id="showgroupDetails-{{ $group->id }}"
                                 data-id="{{$group->id}}">
                                <div class="modal-dialog">
                                    <div class="modal-content modalContentChoose modal-All">
                                        <div class="d-flex justify-content-end m-3">
                                            <button type="button" class="btn-close btn-close-choose"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close" id="closeChoose"></button>
                                        </div>
                                        <div class="modal-body d-flex justify-content-between">
                                            <button class="btn-group mb-2" type="submit" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModalReport">
                                                Group Details
                                            </button>
                                            <button class="btn-report mb-2" type="submit" data-bs-toggle="modal"
                                                    data-bs-target="#moveGroup-{{ $group->id }}">
                                                Move group
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- popup choose tourguide -->
                            <div class="modal "
                                 id="moveGroup-{{ $group->id }}"
                                 data-id="{{$group->id}}">
                                <div class="modal-dialog">
                                    <div class="modal-content modalContentChoose modal-All">
                                        <div class="d-flex justify-content-between p-4">
                                            <h6 class="modal-title text-danger" id="exampleModalLabel">Recommended
                                                Activity
                                                :</h5>
                                                <button type="button" class="btn-close btn-close-choose"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('groupMove') }}" method="post">
                                                @csrf
                                                <input type="text" name="group_id" value="{{ $group->id }}"
                                                       hidden>
                                                {{--                                                @if($group->group_color->color == null)--}}
                                                {{--                                                <div class="activity mb-lg-3">--}}
                                                {{--                                                    <h6 class="title-choose mb-2">Select color</h6>--}}
                                                {{--                                                    <input style="width:200px;right: 66px;top: 16px;position: absolute;"--}}
                                                {{--                                                           type="color" name="color">--}}
                                                {{--                                                </div>--}}
                                                {{--                                                @endif--}}

                                                <input type="text" name="supervisor_old"
                                                       value="{{ $group->supervisor_accept_id }}" hidden>

                                                <div class="activity mt-4">
                                                    <h6 class="title-choose mb-3">Select Activity</h6>
                                                    <div class="form-check">
                                                        <select style="padding: 5px;" name="activity_id"
                                                                class="selectform form-select activitySelect"
                                                                id="activitySelect">
                                                            @foreach($activities as $activity)
                                                                <option
                                                                    value="{{ $activity->id }}">{{ $activity->title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="activity mt-3">
                                                    <h6 class="title-choose mb-3">Select Tourguide</h6>
                                                    <div class="form-check">
                                                        <select style="padding: 5px;" name="supervisor_accept_id"
                                                                class="form-select selectform tourGuideSelect"
                                                                id="tourGuideSelect">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="button mt-3 d-flex justify-content-center">
                                                    <button class="btn-accept mb-2" type="submit">
                                                        Move group
                                                    </button>
                                                </div>
                                            </form>
                                            <!-- <div class="d-flex justify-content-end">
                                              <button class="btn-select mb-2 mt-3" type="submit">Done</button>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- popup table -->
                            <div class="modal " id="exampleModalReport" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Group Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table border">
                                                <thead>
                                                <tr>
                                                    <th scope="col" class="color">First</th>
                                                    <th scope="col" class="color">Last</th>
                                                    <th scope="col" class="color">Handle</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>Mark</td>
                                                    <td>Otto</td>
                                                    <td>@mdo</td>
                                                </tr>
                                                <tr>
                                                    <td>Jacob</td>
                                                    <td>Thornton</td>
                                                    <td>@fat</td>
                                                </tr>
                                                <tr>
                                                    <td>Jacob</td>
                                                    <td>Thornton</td>
                                                    <td>@twitter</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- </div> -->
                    </div>
                </div>
            @endforeach
        </div>
    </content>

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
    <!-- <script src="js/plugins/smooth-scrollbar.min.js"></script> -->
    <!-- <script src="js/plugins/chartjs.min.js"></script> -->
    <!-- <script src="js/plugins/threejs.js"></script> -->
    <!-- <script src="js/plugins/orbit-controls.js"></script> -->
    <!-- dashboard Js -->
    <script src="{{asset('museum/js/app.min.js')}}"></script>
    <!-- custom Js -->
    <!-- <script src="js/custom.js"></script> -->
    <!-- <script src="js/jquery.min.js"></script> -->
    <!-- <script>
      $(document).ready(function(){
        $("a.open-modal").click(function(){
          $(this).modal({
            fadeDuration:200,
            showClose:false
          })
          return false;
        })
      })
    </script> -->

    <script>


        // $('.box-color').on('click', function(){
        //     var color = $(this).data('color');
        //     alert(color);
        // });


        {{--$('.box-color').on('click', function () {--}}
        {{--    var boxColor = $(this).data('color');--}}
        {{--    var group = $(this).data('group');--}}
        {{--    var url = '{{ route('groupColor') }}';--}}
        {{--    $.ajax({--}}
        {{--        url: url,--}}
        {{--        type: 'post',--}}
        {{--        _token: '{{ csrf_token() }}',--}}
        {{--        data: {--}}
        {{--            'groupId': group,--}}
        {{--            'boxColor': boxColor,--}}
        {{--        },--}}
        {{--        success: function () {--}}
        {{--            // location.reload();--}}
        {{--        }--}}
        {{--    })--}}
        {{--})--}}

        function playAudio() {
            var x = new Audio('{{ asset('sound/eventually-590.ogg') }}');
            // Show loading animation.
            var playPromise = x.play();

            if (playPromise !== undefined) {
                playPromise.then(_ => {
                    x.play();
                })
                    .catch(error => {
                    });

            }
        }

        $('.activitySelect').on('click', function () {
            var activity = $(this).val();

            // var color = $('.input-color').val();
            // alert(color);
            var url = '{{ route('selectTourguide') }}';

            // alert('color : '+boxColor + ' group Id : '+group);
            $.ajax({
                url: url,
                type: 'post',
                _token: '{{ csrf_token() }}',
                data: {
                    'activity_id': activity,
                },
                success: function (data) {
                    $('.tourGuideSelect').html(data);
                }
            })
        })

        $('#closeChoose').on('click', function () {
            location.reload();
        })

        $(document).ready(function () {
            toastr.options.timeOut = 5000;
            @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}');
            @elseif(Session::has('success'))
            toastr.success('{{ Session::get('success') }}');
            @endif
        });


    </script>
@endsection
