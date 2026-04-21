@if($entry->status == 0)
<form action="{{ route('user.reject', $entry->userId) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-sm btn-danger" title="Reject User">
        <i class="la la-times"></i> Reject
    </button>
</form>
@endif
