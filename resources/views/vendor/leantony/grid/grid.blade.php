@extends($grid->getRenderingTemplateToUse())
@section('data')
    <div class="row">
        @if($grid->hasButtons('toolbar'))
            <div class="col-md-{{ $grid->getGridToolbarSize()[1] }}">
                <div class="pull-right">
                    @foreach($grid->getButtons('toolbar') as $button)
                        {!! $button->render() !!}
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <form action="" method="GET" id="{{ $grid->getFilterFormId() }}">
    </form>
    <div class="table-responsive grid-wrapper">
        <table class="{{ $grid->getClass() }}" style="font-size: 9pt">
            <thead class="{{ $grid->getHeaderClass() }}">
            <tr class="filter-header">
                @foreach($columns as $column)

                    @if($loop->first)

                        @if($column->isSortable)
                            <th scope="col"
                                class="{{ is_callable($column->columnClass) ? call_user_func($column->columnClass) : $column->columnClass }}"
                                title="click to sort by {{ $column->key }}">
                                <a data-trigger-pjax="1" class="data-sort"
                                   href="{{ $grid->getSortUrl($column->key, $grid->getSelectedSortDirection()) }}">
                                    @if(isset($grid->columns[$column->key]['basic']['name']))
                                        @php $name = $grid->columns[$column->key]['basic']['name']; @endphp
                                    @else
                                        @php $name = $column->key; @endphp
                                    @endif

                                    @if($column->useRawHtmlForLabel)
                                        {!! $name !!}
                                    @else
                                        {{ $name }}
                                    @endif
                                </a>
                            </th>
                        @else
                            <th class="{{ is_callable($column->columnClass) ? call_user_func($column->columnClass) : $column->columnClass }}">
                                @if($column->useRawHtmlForLabel)
                                    {!! $column->name !!}
                                @else
                                    {{ $column->name }}
                                @endif
                            </th>
                        @endif
                    @else
                        @if($column->isSortable)
                            <th scope="col" title="click to sort by {{ $column->key }}"
                                class="{{ is_callable($column->columnClass) ? call_user_func($column->columnClass) : $column->columnClass }}">
                                <a data-trigger-pjax="1" class="data-sort"
                                   href="{{ $grid->getSortUrl($column->key, $grid->getSelectedSortDirection()) }}">
                                    @if($column->useRawHtmlForLabel)
                                        {!! $column->key !!}
                                    @else
                                        {{ $column->key }}
                                    @endif
                                </a>
                            </th>
                        @else
                            <th scope="col"
                                class="{{ is_callable($column->columnClass) ? call_user_func($column->columnClass) : $column->columnClass }}">
                                @if($column->useRawHtmlForLabel)
                                    {!! $column->name !!}
                                @else
                                    {{ $column->name }}
                                @endif
                            </th>
                        @endif
                    @endif
                @endforeach
                <th></th>
            </tr>
            @if($grid->shouldRenderGridFilters())
                <tr>
                    {!! $grid->renderGridFilters() !!}
                </tr>
            @endif
            </thead>
            <tbody>
            @if($grid->hasItems())
                @if($grid->warnIfEmpty())
                    <div class="alert alert-warning" role="alert">
                        <strong><i class="fa fa-exclamation-triangle"></i>&nbsp;No data present!.</strong>
                    </div>
                @endif
            @else
                @foreach($grid->getData() as $item)
                    @if($grid->allowsLinkableRows())
                        @php
                            $callback = call_user_func($grid->getLinkableCallback(), $grid->transformName(), $item);
                        @endphp
                        @php
                            $trClassCallback = call_user_func($grid->getRowCssStyle(), $grid->transformName(), $item);
                        @endphp
                        <tr class="{{ trim("linkable " . $trClassCallback) }}" data-url="{{ $callback }}">
                    @else
                        @php
                            $trClassCallback = call_user_func($grid->getRowCssStyle(), $grid->transformName(), $item);
                        @endphp
                        <tr class="{{ $trClassCallback }}">
                            @endif
                            @foreach($columns as $column)
                                @if(is_callable($column->data))
                                    @if($column->useRawFormat)
                                        <td class="{{ $column->rowClass }}">
                                            {!! call_user_func($column->data, $item, $column->key) !!}
                                        </td>
                                    @else
                                        @if(isset($grid->columns[$column->key]['basic']['link_neo']))
                                            @php $link = $grid->columns[$column->key]['basic']['link_neo']; @endphp
                                        @else
                                            @php $link = ""; @endphp
                                        @endif
                                        <td class="{{ $column->rowClass }}">
                                            @if(isset($link) && $link!="")
                                                <a href="{{$link . call_user_func($column->data , $item, $column->key) }}" target="_blank">
                                                    {{ call_user_func($column->data , $item, $column->key) }}
                                                </a>
                                            @else
                                                {{ call_user_func($column->data , $item, $column->key) }}
                                            @endif
                                        </td>
                                    @endif
                                @else
                                    @if($column->useRawFormat)
                                        <td class="{{ $column->rowClass }}">
                                            {!! $item->{$column->key} !!}
                                        </td>
                                    @else
                                        <td class="{{ $column->rowClass }}">
                                            {{ $item->{$column->key} }}
                                        </td>
                                    @endif
                                @endif
                                @if($loop->last && $grid->hasButtons('rows'))
                                    <td>
                                        <div class="pull-right">
                                            @foreach($grid->getButtons('rows') as $button)
                                                @if(call_user_func($button->renderIf, $grid->transformName(), $item))
                                                    {!! $button->render(['gridName' => $grid->transformName(), 'gridItem' => $item]) !!}
                                                @else
                                                    @continue
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        @endforeach
                        @if($grid->shouldShowFooter())
                            <tr class="{{ $grid->getGridFooterClass() }}">
                                @foreach($columns as $column)
                                    @if($column->footer === null)
                                        <td></td>
                                    @else
                                        <td>
                                            <b>{{ call_user_func($column->footer) }}</b>
                                        </td>
                                    @endif
                                    @if($loop->last)
                                        <td></td>
                                    @endif
                                @endforeach
                            </tr>
                        @endif
                    @endif
            </tbody>
        </table>
    </div>
@endsection
@push('grid_js')
    <script>
      (function($) {
        var grid = "{{ '#' . $grid->getId() }}";
        var filterForm = "{{ '#' . $grid->getFilterFormId() }}";
        var searchForm = "{{ '#' . $grid->getSearchFormId() }}";
        _grids.grid.init({
          id: grid,
          filterForm: filterForm,
          dateRangeSelector: '.date-range',
          searchForm: searchForm,
          pjax: {
            pjaxOptions: {
              scrollTo: false,
            },
            // what to do after a PJAX request. Js plugins have to be re-intialized
            afterPjax: function(e) {
              _grids.init();
            },
          },
        });
      })(jQuery);
    </script>
@endpush

<style>
    .laravel-grid .grid-wrapper {
        margin-top: 0 !important;
    }
    .card-body {
        padding-top: 0 !important;
    }
</style>