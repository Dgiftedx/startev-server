<table>
    <thead>
    <tr>
        <th colspan="32 " style="text-align: center; color: red;">VENTURE SETTLEMENT LIST</th>
    </tr>
    <tr style="color: blue;">
        <th colspan="4" style="text-align: center; font-size: x-large">Settlement ID</th>
        <th colspan="4" style="text-align: center;">Venture Name</th>
        <th colspan="4" style="text-align: center;">Pending Total Amount (NGN)</th>
        <th colspan="4" style="text-align: center;">Bank Name</th>
        <th colspan="4" style="text-align: center;">Account Name</th>
        <th colspan="4" style="text-align: center;">Account Number</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data['biz'] as $value)
        <tr>
            <td colspan="4" style="text-align: center;">{{ $value->batch_ref_id }}</td>
            <td colspan="4" style="text-align: center;">{{ $value->business->name }}</td>
            <td colspan="4" style="text-align: center;">{{ number_format($value->total,2) }}</td>
            <td colspan="4" style="text-align: center;">{{ $value->business->bank_name  }}</td>
            <td colspan="4" style="text-align: center;">{{ $value->business->account_name  }}</td>
            <td colspan="4" style="text-align: center;">{{ $value->business->account_number  }}</td>
        </tr>
    @endforeach
    </tbody>
    <thead>
    <tr>
        <th colspan="32" style="text-align: center; color: red;">STORE SETTLEMENT LIST</th>
    </tr>
    <tr style="color: blue;">
        <th colspan="4" style="text-align: center; font-size: x-large">Settlement ID</th>
        <th colspan="4" style="text-align: center;">Store Name</th>
        <th colspan="4" style="text-align: center;">Pending Total Amount (NGN)</th>
        <th colspan="4" style="text-align: center;">Bank Name</th>
        <th colspan="4" style="text-align: center;">Account Name</th>
        <th colspan="4" style="text-align: center;">Account Number</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data['store'] as $value)
        <tr>
            <td colspan="4" style="text-align: center;">{{ $value->reference_id }}</td>
            <td colspan="4" style="text-align: center;">{{ $value->store->store_name }}</td>
            <td colspan="4" style="text-align: center;">{{ number_format($value->total,2) }}</td>
            <td colspan="4" style="text-align: center;">{{ $value->store->bank_name  }}</td>
            <td colspan="4" style="text-align: center;">{{ $value->store->account_name  }}</td>
            <td colspan="4" style="text-align: center;">{{ $value->store->account_number  }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
