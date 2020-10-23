@extends('admin.base')

@section('main')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Thông tin chi tiết</h2>

                        <div class="clearfix"></div>
                    </div>
                    {{--<div class="x_content" style="overflow: auto;">--}}
                        {{--<table id="datatable-checkbox" class="table table-bordered">--}}
                            {{--<tr>--}}
                                {{--<th>STT</th>--}}
                                {{--<th>UID</th>--}}
                                {{--<th>Action Id</th>--}}
                                {{--<th>Action Type</th>--}}
                                {{--<th>Status</th>--}}
                                {{--<th>Data</th>--}}
                            {{--</tr>--}}
                            {{--@foreach($actionFacebooks as $key => $actionFacebook)--}}
                                {{--<tr>--}}
                                    {{--<td>{{ $key+1 }}</td>--}}
                                    {{--<td>{{ $actionFacebook->uid }}</td>--}}
                                    {{--<td>{{ $actionFacebook->action_id }}</td>--}}
                                    {{--<td>{{ $actionFacebook->action_type }}</td>--}}
                                    {{--<td>{{ $actionFacebook->status }}</td>--}}
                                    {{--<td>{{ json_encode($actionFacebook->data) }}</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
                        {{--</table>--}}
                        {{--{{ $actionFacebooks->links() }}--}}
                    {{--</div>--}}
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#General">General</a></li>
                            <li><a data-toggle="tab" href="#Friend">Friend</a></li>
                            <li><a data-toggle="tab" href="#Group">Group</a></li>
                            <li><a data-toggle="tab" href="#Like">Like</a></li>
                            <li><a data-toggle="tab" href="#Share">Share</a></li>
                            <li><a data-toggle="tab" href="#active">Activities Log</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="General" class="tab-pane fade in active">
                                <h3>HOME</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                            </div>
                            <div id="Friend" class="tab-pane fade">
                                <h3>Menu 1</h3>
                                <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>
                            <div id="Group" class="tab-pane fade">
                                <h3>Menu 2</h3>
                                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
                            </div>
                            <div id="Like" class="tab-pane fade">
                                <h3>Menu 3</h3>
                                <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                            </div>
                            <div id="Share" class="tab-pane fade">
                                <h3>Menu 3</h3>
                                <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                            </div>
                            <div id="active" class="tab-pane fade">
                                <h3>Menu 3</h3>
                                <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection