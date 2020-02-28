
    <table class="table table-bordered">
        <thead>
        <tr class="text-center h1">
            <th colspan="4" class="text-capitalize">
                @if( $status == '')
                    All
                @elseif( $status == 0)
                    Pending
                @elseif( $status == 1)
                    Published
                @else()
                    Trashed
                @endif
                Feeds
            </th>
        </tr>
        <tr>
            {{--<th class="sorting"--}}
                {{--data-sorting_type="asc"--}}
                {{--data-column_name="id"--}}
                {{--style="cursor: pointer"--}}
            {{-->--}}
                {{--ID <span id="id_icon"></span>--}}
            {{--</th>--}}
            <th>
                Feed By
            </th>
            <th class="sorting">
                Feed Type
            </th>
            <th class="sorting">
                Feeds Content
            </th>
            <th class="sorting">
                Status
            </th>
            <th class="text-nowrap">
                Action
            </th>
        </tr>
        </thead>
        <tbody>
            @foreach($feeds as $feed)
                <tr>
                    {{--<td>--}}
                        {{--{{ $feed->id }}--}}
                    {{--</td>--}}
                    <td>
                        {{ $feed->user->name }}
                    </td>
                    <td class="text-capitalize">
                        {{ $feed->postType }}
                    </td>
                    <td>
                        {!!  str_limit($feed->content, 250) !!}
                    </td>
                    <td>
                        @if($feed->status == 1)
                            <span class="text-success">Published</span>
                        @elseif($feed->status == 0)
                            <span class="text-warning">Pending</span>
                        @else
                            <span class="text-danger">Trashed</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <a href="./feed/{{ $feed->id }}" class="btn btn-sm btn-info waves-effect waves-light text-white">View </a>

                        @if($feed->status != 1 && $feed->status != 0 )
                            <a href="#"
                               class="btn btn-sm btn-warning waves-effect waves-light text-white review"
                               id="{{ $feed->id }}"
                            >Review</a>
                        @endif
                        @if($feed->status == 0)
                            <a href="#"
                               class="btn btn-sm btn-warning waves-effect waves-light text-white publish"
                               id="{{ $feed->id }}"
                            >Publish</a>
                            <br>
                        @endif
                        @if($feed->status == 1 || $feed->status == 0)
                            <a href="#"
                               class="btn btn-sm btn-danger waves-effect waves-light text-white trash"
                               id="{{ $feed->id }}"
                            >Trash</a>
                        @endif

                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <div>
        {{ $feeds->links() }}
    </div>

    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_status" id="hidden_status" value="{{$status}}" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="{{$sort_by}}" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="{{$sort_type}}" />