@foreach($report->files as $file)
@if($file->opendata == 1)
<p>
    <a href="{{ url($file->file_path) }}">
        <i class="i-fa6-solid-file" aria-hidden="true"></i> {{ $file->name }}
    </a><br />
    <span class="text-gray-400 ml-3">&ndash; {{ $file->created_at }}</span>
</p>
@endif
@endforeach