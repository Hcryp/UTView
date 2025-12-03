<h1>Wiki Library</h1>
@foreach($docs as $d)
    <article>
        <h3><a href="{{ route('wiki.read', $d->slug) }}">{{ $d->title }}</a></h3>
        <p>{{ $d->summary }}</p>
    </article>
@endforeach