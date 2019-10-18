<table>
    <thead>
    <tr>
        <th colspan="4" style="text-align: center;">Name</th>
        <th colspan="4" style="text-align: center;">Email Address</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $value)
        <tr>
            <td colspan="4">{{ $value->name }}</td>
            <td colspan="4">{{ $value->email }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
