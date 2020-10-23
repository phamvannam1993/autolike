<table>
    <tr>
        <th>#</th>
        <th>Email</th>
        <th>Email pass</th>
        <th>Passwd</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Birthday</th>
        <th>Sex</th>
        <th>Created date</th>
        <th>Created IP</th>
        <th>Active</th>
    </tr>
    @foreach($facebookAccounts as $key => $facebookAccount)
    <tr>
        <td>{{ $key }}</td>
        <td>{{ $facebookAccount->email }}</td>
        <td>{{ $facebookAccount->email_pass }}</td>
        <td>{{ $facebookAccount->passwd }}</td>
        <td>{{ $facebookAccount->firstname }}</td>
        <td>{{ $facebookAccount->lastname }}</td>
        <td>{{ $facebookAccount->birthday }}</td>
        <td>{{ $facebookAccount->sex }}</td>
        <td>{{ $facebookAccount->created_date }}</td>
        <td>{{ $facebookAccount->created_ip }}</td>
        <td>{{ $facebookAccount->active }}</td>
    </tr>
    @endforeach
</table>
{{ $facebookAccounts->links() }}
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 15px;
    }
</style>