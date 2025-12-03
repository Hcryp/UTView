<h1>Document Management</h1>
<a href="{{ route('docs.create') }}">+ New Doc</a>

<table>
    <tr><th>Title</th><th>Status</th><th>Action</th></tr>
    @foreach($docs as $d)
    <tr>
        <td>{{ $d->title }}</td>
        <td>{{ $d->isPub ? 'Public' : 'Draft' }}</td>
        <td>
            <a href="{{ route('docs.edit', $d->id) }}">Edit</a>
            <form action="{{ route('docs.destroy', $d->id) }}" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button>Del</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>