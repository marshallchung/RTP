@if((sizeof($files) > 0) || (sizeof($directories) > 0))
<table class="table table-responsive table-condensed table-striped hidden-xs">
  <thead>
    <th style='width:50%;'>{{ Lang::get('laravel-filemanager::lfm.title-item') }}</th>
    <th class="p-2 font-normal text-left border-r last:border-r-0">{{ Lang::get('laravel-filemanager::lfm.title-size')
      }}</th>
    <th class="p-2 font-normal text-left border-r last:border-r-0">{{ Lang::get('laravel-filemanager::lfm.title-type')
      }}</th>
    <th class="p-2 font-normal text-left border-r last:border-r-0">{{
      Lang::get('laravel-filemanager::lfm.title-modified') }}</th>
    <th class="p-2 font-normal text-left border-r last:border-r-0">{{ Lang::get('laravel-filemanager::lfm.title-action')
      }}</th>
  </thead>
  <tbody>
    @foreach($items as $item)
    <tr>
      <td class="p-2 border-r last:border-r-0">
        <i class="fa {{ $item->icon }}"></i>
        <a class="{{ $item->is_file ? 'file' : 'folder'}}-item clickable"
          data-id="{{ $item->is_file ? $item->url : $item->path }}">
          {{ str_limit($item->name, $limit = 20, $end = '...') }}
        </a>
      </td>
      <td class="p-2 border-r last:border-r-0">{{ $item->size }}</td>
      <td class="p-2 border-r last:border-r-0">{{ $item->type }}</td>
      <td class="p-2 border-r last:border-r-0">{{ $item->time }}</td>
      <td class="p-2 border-r last:border-r-0">
        @if($item->is_file)
        <a href="javascript:trash('{{ $item->name }}')">
          <i class="fa fa-trash fa-fw"></i>
        </a>
        @if($item->thumb)
        <a href="javascript:cropImage('{{ $item->name }}')">
          <i class="fa fa-crop fa-fw"></i>
        </a>
        <a href="javascript:resizeImage('{{ $item->name }}')">
          <i class="fa fa-arrows fa-fw"></i>
        </a>
        @endif
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

<table class="table visible-xs">
  <tbody>
    @foreach($items as $item)
    <tr>
      <td class="p-2 border-r last:border-r-0">
        <div class="media" style="height: 70px;">
          <div class="media-left">
            <div class="square {{ $item->is_file ? 'file' : 'folder'}}-item clickable"
              data-id="{{ $item->is_file ? $item->url : $item->path }}">
              @if($item->thumb)
              <img src="{{ $item->thumb }}">
              @else
              <i class="fa {{ $item->icon }} fa-5x"></i>
              @endif
            </div>
          </div>
          <div class="media-body" style="padding-top: 10px;">
            <div class="media-heading">
              <p>
                <a class="{{ $item->is_file ? 'file' : 'folder'}}-item clickable"
                  data-id="{{ $item->is_file ? $item->url : $item->path }}">
                  {{ str_limit($item->name, $limit = 20, $end = '...') }}
                </a>
                &nbsp;&nbsp;
                {{-- <a href="javascript:rename('{{ $item->name }}')">
                  <i class="fa fa-edit"></i>
                </a> --}}
              </p>
            </div>
            <p style="color: #aaa;font-weight: 400">{{ $item->time }}</p>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@else
<p>{{ trans('laravel-filemanager::lfm.message-empty') }}</p>
@endif