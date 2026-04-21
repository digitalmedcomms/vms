@if($entry->status == 0)
<form action="{{ route('user.approve', $entry->userId) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-sm btn-success" title="Approve User">
        <i class="la la-check"></i> Approve
    </button>
</form>
@endif
