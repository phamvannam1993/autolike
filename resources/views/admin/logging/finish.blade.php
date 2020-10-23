@extends('admin.base')

@section('main')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>bug list  {{$name}}</h2>
                        <div class="clearfix"></div>
                    </div>
                    <a href="{{route('admin.logging', ['name' => $name])}}" class="btn btn-primary">Loging list</a>
                    <a href="{{route('admin.logging.bug', ['name' => $name])}}" class="btn btn-primary">Bug list</a>
                    <div class="x_content" style="overflow:auto">
                        <table id="datatable-checkbox" class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <th>Info</th>
                                <th>Image</th>
                            </tr>
                            @foreach($loggingList as $key => $logging)
                                <tr>
                                    <td style="width: 20px">{{ isset($_REQUEST['page']) ? $countAll - ($_REQUEST['page']-1)*50 - $key : $countAll - $key }}</td>
                                    <td style="width: 800px;word-break: break-all;word-wrap: break-word;">
                                        <?php if (isset($logging['info'])) {
                                            $info = json_decode($logging['info'], true);
                                            if(isset($info['token'])) {
                                                echo 'Token : '.$info['token'].'<br>';
                                                unset($info['token']);
                                            }
                                            echo 'date Time : ' . $logging['created_at'] . '<br>';
                                            foreach ($info as $key => $value) {
                                                if (is_array($value)) {
                                                    echo $key . ' : ' . json_encode($value) . '<br>';
                                                } else {
                                                    echo $key . ' : ' . $value . '<br>';
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td style="width: 700px;">
                                        @if(isset($logging['image']))
                                            <img style="width: 300px" src="data:image/gif;base64,{{$logging['image']}}">
                                        @endif
                                        @if(isset($logging['image1']))
                                            <img style="width: 300px"
                                                 src="data:image/gif;base64,{{$logging['image1']}}">
                                        @endif
                                        @if(isset($logging['image2']))
                                            <img style="width: 300px"
                                                 src="data:image/gif;base64,{{$logging['image2']}}">
                                        @endif
                                        @if(isset($logging['image3']))
                                            <img style="width: 300px"
                                                 src="data:image/gif;base64,{{$logging['image3']}}">
                                        @endif
                                        @if(isset($logging['image4']))
                                            <img style="width: 300px"
                                                 src="data:image/gif;base64,{{$logging['image4']}}">
                                        @endif
                                        @if(isset($logging['image5']))
                                            <img style="width: 300px"
                                                 src="data:image/gif;base64,{{$logging['image5']}}">
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        {{ $loggingList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


