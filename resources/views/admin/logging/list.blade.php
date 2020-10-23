@extends('admin.base')

@section('main')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                <h2>logging {{$name}}</h2>
                <div class="clearfix"></div>
            </div>
            <form method="get" action="">

                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" placeholder="token" name="token" class="form-control" value="{{$token}}">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" placeholder="Device Name" name="DeviceName" class="form-control" value="{{$DeviceName}}">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" placeholder="machine" name="machine" class="form-control" value="{{$machine}}">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" placeholder="package" name="package" class="form-control" value="{{$package}}">
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" value="Tìm kiếm">
                        <a class="btn btn-danger" id="delete" href="{{route('admin.logging.delete', ['name' => $name])}}">Delete Logging</a>
                        <a class="btn btn-danger" id="delete" href="?page={{isset($_REQUEST['page']) ? $_REQUEST['page'] : 1 }}&action=delete">Delete Logging Page</a>
                        <a href="{{route('admin.logging.bug', ['name' => $name])}}" class="btn btn-primary">Bug list</a>
                    </div>
                </div>
            </form>
            <form method="post" id="LoggingForm" action="">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <div class="x_content" style="overflow:auto">
                    <table id="datatable-checkbox" class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <th><input type="checkbox" class="checkAll"></th>
                            <th>Info</th>
                            <th>Image</th>
                        </tr>
                        @foreach($loggingList as $key => $logging)
                            <tr>
                                <td style="width: 20px">{{ isset($_REQUEST['page']) ? $countAll - ($_REQUEST['page']-1)*10 - $key : $countAll - $key }}</td>
                                <td style="width: 20px"><input class="checkBox" type="checkbox" name="uids[]" value="{{ $logging->_id }}"></td>
                                <td style="width: 1200px;word-break: break-all;word-wrap: break-word;">
                                    <?php if(isset($logging['info'])) {
                                            $info = json_decode($logging['info'], true);
                                            if(isset($info['token'])) {
                                                echo 'Token : '.$info['token'].'<br>';
                                                unset($info['token']);
                                            }
                                            if(isset($info['images'])) {
                                                unset($info['images']);
                                            }
                                            echo 'date Time : '.$logging['created_at'].'<br>';
                                            foreach ($info as $key => $value) {
                                                if(is_array($value)) {
                                                    echo $key.' : '.json_encode($value).'<br>';
                                                } else {
                                                    echo $key.' : '.$value.'<br>';
                                                }
                                            }
                                        }
                                    ?>
                                    <a href="javascript:void(0)" class="btn btn-info btnAddLog" data-log="{{$logging->_id}}">Report</a>
                                        <a href="javascript:void(0)" class="btn btn-danger btnDeleteLog" data-log="{{$logging->_id}}">Delete</a>
                                </td>
                                <td style="width: 700px;">
                                    @if(isset($logging['image']))
                                        <img style="width: 300px" src="data:image/gif;base64,{{$logging['image']}}">
                                    @endif
                                    @if(isset($logging['image1']))
                                        <img style="width: 300px" src="data:image/gif;base64,{{$logging['image1']}}">
                                    @endif
                                    @if(isset($logging['image2']))
                                        <img style="width: 300px" src="data:image/gif;base64,{{$logging['image2']}}">
                                    @endif
                                    @if(isset($logging['image3']))
                                        <img style="width: 300px" src="data:image/gif;base64,{{$logging['image3']}}">
                                    @endif
                                    @if(isset($logging['image4']))
                                        <img style="width: 300px" src="data:image/gif;base64,{{$logging['image4']}}">
                                    @endif
                                    @if(isset($logging['image5']))
                                        <img style="width: 300px" src="data:image/gif;base64,{{$logging['image5']}}">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $loggingList->links() }}
                </div>
            </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Bootstrap -->
@push('pageScripts')
<script>
    $('.btnAddLog').click(function () {
        var logId = $(this).attr('data-log')
        var btn = $(this);
        handle(logId, btn, 1)
    })

    $('.btnDeleteLog').click(function () {
        var logId = $(this).attr('data-log')
        var btn = $(this);
        handle(logId, btn, 2)
    })

    function handle(logId, btn, type) {
        $.ajax({
            type: "POST",
            url: 'https://log.autofarmer.xyz/logging/saveLog',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                logId: logId,
                type: type
            },
            beforeSend: function() {
                btn.prop('disabled', true);
                btn.prepend('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                btn.closest('tr').remove();
            },
            complete: function(){
                btn.prop('disabled', false);
                btn.find('i.fa-spinner').remove();
            }
        });
    }

    $('.btnSubmit').click(function () {
        $('#LoggingForm').submit();
    })

    $('.checkAll').click(function () {
        if ($('.checkAll').is(':checked')) {
            $('body .checkBox').attr('checked', true);
        } else {
            $('body .checkBox').attr('checked', false);
        }
    })

</script>
@endpush

