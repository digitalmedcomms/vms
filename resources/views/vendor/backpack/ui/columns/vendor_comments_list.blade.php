@php
    $comments = $entry->comments()->with('user')->orderBy('insert_date', 'desc')->get();
@endphp

@if($comments->count())
    <table class="table table-striped table-hover mb-0">
        <thead>
            <tr>
                <th>Rating</th>
                <th>Comment</th>
                <th>User</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
                <tr>
                    <td>
                        @include('vendor.backpack.ui.columns.star_rating', ['column' => ['value' => $comment->rating]])
                    </td>
                    <td>{{ $comment->comment }}</td>
                    <td>{{ $comment->user->name ?? 'Unknown User' }}</td>
                    <td>{{ $comment->insert_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-muted">No comments yet.</p>
@endif
