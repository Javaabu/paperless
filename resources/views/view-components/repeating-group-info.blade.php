<div>
    @php
        $headings = $getHeadings();
        $rows = $getRows();
    @endphp
    <table class="table table-bordered mb-0">
        <thead>
        <tr>
            <th>#</th>
            @foreach($headings as $heading)
                <th>{{ $heading }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                <th scope="row">{{ $loop->index + 1 }}</th>
                @foreach($row as $value)
                    <td>{!! $value !!}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
