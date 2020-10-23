<div class="x_content">
    <div class="x_content" style="overflow: auto;">
        <table id="datatable-checkbox" class="table table-bordered">
            <tr>
                <th>STT</th>
                <th>DeviceID</th>
                <th>IP</th>
                <th>DateTime</th>
                <th>Exception</th>
            </tr>
            @foreach($exceptionList as $key => $exception)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $exception->device_id }}</td>
                    <td>{{ $exception->ip }}</td>
                    <td>{{ date('Y/m/d H:i:s', strtotime($exception->created_at)) }}</td>
                    <td>{{ $exception->exception }}</td>
                </tr>
            @endforeach
        </table>
        {{ $exceptionList->links() }}
    </div>
</div>