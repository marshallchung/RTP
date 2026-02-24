@foreach($imageDatum->files as $file)
<p>
    <a href="{{ url($file->path) }}">
        <i class="i-fa6-solid-file" aria-hidden="true"></i> {{ $file->name }}
    </a><br />
    <span class="text-gray-400 ml-3">&ndash; {{ $file->created_at }}</span>
</p>
@endforeach